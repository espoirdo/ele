@extends('layouts.app')

@section('title', 'Mot de passe oublié')

@section('content')
<div style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 120px 24px 48px;">
    <div style="width: 100%; max-width: 440px; background: #FFFFFF; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 40px;">

        <h1 style="font-family: 'Eras Medium ITC', serif; font-weight: 700; font-size: 22px; color: #000000; text-align: center; margin-bottom: 12px;">
            Mot de passe oublié
        </h1>

        <p style="font-family: 'Poppins', sans-serif; font-size: 14px; color: #666666; text-align: center; line-height: 1.6; margin-bottom: 28px;">
            Pas de souci. Indiquez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
        </p>

        @if (session('status'))
            <div style="background: #E8F5E9; color: #2E7D32; padding: 12px 16px; border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 13px; margin-bottom: 20px;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <label style="display: block; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500; color: #444444; margin-bottom: 6px;">
                Adresse email
            </label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   placeholder="votre@email.com"
                   style="width: 100%; border: 1.5px solid #E0E0E0; border-radius: 8px; padding: 12px 16px; font-family: 'Poppins', sans-serif; font-size: 14px; outline: none; -webkit-tap-highlight-color: transparent;"
                   onfocus="this.style.borderColor='#CC0000'; this.style.boxShadow='0 0 0 3px rgba(204,0,0,0.08)';"
                   onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">

            @error('email')
                <p style="color: #CC0000; font-family: 'Poppins', sans-serif; font-size: 12px; margin-top: 6px;">{{ $message }}</p>
            @enderror

            <button type="submit"
                    style="width: 100%; margin-top: 24px; background: linear-gradient(to right, #CC0000, #910000); color: #FFFFFF; border: none; border-radius: 40px; padding: 14px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; cursor: pointer; outline: none; -webkit-tap-highlight-color: transparent; transition: all 0.25s ease;">
                Envoyer le lien de réinitialisation
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px;">
            <a href="{{ route('login') }}"
               style="font-family: 'Poppins', sans-serif; font-size: 13px; color: #CC0000; text-decoration: none; font-weight: 500;">
                Retour à la connexion
            </a>
        </div>

    </div>
</div>
@endsection
