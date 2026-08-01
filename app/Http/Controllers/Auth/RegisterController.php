<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'cgv' => ['required', 'accepted'],
            'captcha' => ['required', 'accepted'],
            'accept_cgu' => ['required', 'accepted'],
            'is_whatsapp' => ['nullable', 'boolean'],
        ], [
            'phone.required' => 'Le numéro de téléphone est obligatoire',
            'country.required' => 'Le pays de provenance est obligatoire',
            'cgv.required' => 'Vous devez accepter les Conditions Générales de Vente (CGV)',
            'cgv.accepted' => 'Vous devez accepter les Conditions Générales de Vente (CGV)',
            'captcha.required' => 'Veuillez confirmer que vous n\'êtes pas un robot',
            'captcha.accepted' => 'Veuillez confirmer que vous n\'êtes pas un robot',
            'accept_cgu.required' => 'Vous devez accepter les CGU, la politique de confidentialité et la politique cookies pour créer un compte.',
            'accept_cgu.accepted' => 'Vous devez accepter les CGU, la politique de confidentialité et la politique cookies pour créer un compte.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'country' => $validated['country'],
            'is_whatsapp' => $request->boolean('is_whatsapp'),
            'role' => 'user',
            'is_blocked' => false,
        ]);

        // Déclenche l'envoi de l'email de vérification (MustVerifyEmail côté User)
        event(new Registered($user));

        // Pas de login automatique : l'utilisateur doit d'abord cliquer sur le lien de vérification
        return redirect()->route('verification.notice')
            ->with('message', 'Un lien de vérification a été envoyé à votre adresse email.');
    }
}
