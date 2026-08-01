@extends('layouts.app')
@section('title', 'Vérifiez votre email')
@section('content')
<div style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 120px 24px 48px;">
    <div style="max-width: 480px; width: 100%; text-align: center; background: white; border-radius: 16px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">

        <div style="width: 72px; height: 72px; background: #FFF5F5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#CC0000" stroke-width="1.8">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
        </div>

        <h1 style="font-family: 'Eras Medium ITC', serif; font-size: 22px; color: #000000; margin-bottom: 12px;">
            Vérifiez votre email
        </h1>

        <p style="font-family: 'Poppins', sans-serif; font-size: 14px; color: #666666; line-height: 1.7; margin-bottom: 28px;">
            Un lien de vérification a été envoyé à votre adresse email.<br>
            Cliquez sur ce lien pour activer votre compte et accéder à toutes les fonctionnalités d'Eledji.
        </p>

        @if(session('status') == 'verification-link-sent')
            <div style="background: #E8F5E9; color: #2E7D32; padding: 12px 16px; border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 13px; margin-bottom: 20px;">
                Un nouvel email de vérification a été envoyé à votre adresse.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    style="width: 100%; background: linear-gradient(to right, #CC0000, #910000); color: white; border: none; border-radius: 40px; padding: 14px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; cursor: pointer; outline: none; margin-bottom: 16px; transition: all 0.25s ease;">
                Renvoyer l'email de vérification
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    style="background: none; border: none; font-family: 'Poppins', sans-serif; font-size: 13px; color: #888888; cursor: pointer; text-decoration: underline;">
                Se déconnecter
            </button>
        </form>

    </div>
</div>
@endsection
