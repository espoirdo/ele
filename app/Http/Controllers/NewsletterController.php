<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\NewsletterSubscriber;

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

        $email = $request->email;

        // Vérifier si l'email existe déjà
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
}