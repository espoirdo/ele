<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterMail;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $email = Str::lower($request->email);

        $existing = NewsletterSubscriber::where('email', $email)->first();

        if ($existing) {
            return redirect()->back()
                ->with('newsletter_success', 'Vous êtes déjà inscrit à notre newsletter !')
                ->withInput();
        }

        // Créer le nouvel abonné
        NewsletterSubscriber::create([
            'email' => $email,
        ]);

        return redirect()->back()
            ->with('newsletter_success', 'Merci ! Votre inscription à la newsletter est confirmée.')
            ->withInput();
    }

    public function index()
    {
        $subscribers = NewsletterSubscriber::query()
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $activeCount = NewsletterSubscriber::query()
            ->when(Schema::hasColumn('newsletter_subscribers', 'is_active'), function ($query) {
                return $query->where('is_active', true);
            })
            ->count();

        $totalCount = NewsletterSubscriber::count();
        $lastSubscriber = NewsletterSubscriber::query()->latest('created_at')->first();
        $campaigns = NewsletterCampaign::with('sender')->latest('sent_at')->take(5)->get();

        return view('admin.newsletter.index', compact('subscribers', 'activeCount', 'totalCount', 'lastSubscriber', 'campaigns'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'sujet' => ['required', 'string', 'max:150'],
            'contenu' => ['required', 'string', 'min:10'],
        ], [
            'sujet.required' => 'Le sujet est obligatoire.',
            'sujet.max' => 'Le sujet ne peut pas dépasser 150 caractères.',
            'contenu.required' => 'Le contenu est obligatoire.',
            'contenu.min' => 'Le contenu doit contenir au moins 10 caractères.',
        ]);

        $query = NewsletterSubscriber::query();
        if (Schema::hasColumn('newsletter_subscribers', 'is_active')) {
            $query->where('is_active', true);
        }

        $subscribers = $query->get();
        $emails = $subscribers->pluck('email')->filter()->unique()->values();

        $sentCount = 0;
        foreach ($emails->chunk(50) as $chunk) {
            foreach ($chunk as $email) {
                Mail::to($email)->send(new NewsletterMail($validated['sujet'], $validated['contenu'], $email));
                $sentCount++;
            }
        }

        NewsletterCampaign::create([
            'sujet' => $validated['sujet'],
            'contenu' => $validated['contenu'],
            'nb_destinataires' => $sentCount,
            'envoye_par' => $request->user()?->id,
            'sent_at' => now(),
        ]);

        return redirect()->route('admin.newsletter.index')->with('success', 'Newsletter envoyée avec succès à ' . $sentCount . ' abonnés.');
    }

    public function subscribers(Request $request)
    {
        $query = NewsletterSubscriber::query();

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        $subscribers = $query->latest('created_at')->paginate(20);

        return view('admin.newsletter.subscribers', compact('subscribers'));
    }

    public function history(Request $request)
    {
        $campaigns = NewsletterCampaign::with('sender')
            ->latest('sent_at')
            ->paginate(20);

        return view('admin.newsletter.history', compact('campaigns'));
    }

    public function destroy($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $subscriber->delete();

        return redirect()->route('admin.newsletter.subscribers')->with('success', 'Abonné supprimé avec succès.');
    }

    public function unsubscribe(Request $request)
    {
        $email = $request->query('email');

        if ($email) {
            $subscriber = NewsletterSubscriber::where('email', Str::lower($email))->first();
            if ($subscriber) {
                if (Schema::hasColumn('newsletter_subscribers', 'is_active')) {
                    $subscriber->update(['is_active' => false]);
                } else {
                    $subscriber->delete();
                }
            }
        }

        return view('newsletter.unsubscribe');
    }
}