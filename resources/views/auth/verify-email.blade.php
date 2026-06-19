@extends('layouts.app')

@section('title', 'Verifier votre email - ELEDJI')

@push('styles')
<style>
:root {
    --rouge: #CC0000;
    --rouge-dark: #910000;
    --vert-doux: #2E7D32;
    --vert-bg: #E8F5E9;
    --gris-bg: #F9F9F9;
    --gris-border: #E0E0E0;
    --texte: #1a1a1a;
    --texte-doux: #666;
    --poppins: 'Poppins', sans-serif;
    --radius: 16px;
    --shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
}
*, *::before, *::after { box-sizing: border-box; }

.verify-page {
    min-height: calc(100vh - 80px);
    padding: 60px 24px;
    background: var(--gris-bg);
    font-family: var(--poppins);
    display: flex;
    align-items: center;
    justify-content: center;
}

.verify-container {
    max-width: 480px;
    width: 100%;
}

.verify-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 48px 40px;
    text-align: center;
}

.verify-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 24px;
    background: var(--vert-bg);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.verify-icon svg {
    width: 40px;
    height: 40px;
    stroke: var(--vert-doux);
}

.verify-title {
    font-family: 'Eras Medium ITC', serif;
    font-size: 24px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 12px;
}

.verify-text {
    font-size: 15px;
    color: var(--texte-doux);
    line-height: 1.6;
    margin: 0 0 32px;
}

.verify-text strong {
    color: var(--texte);
}

.btn-resend {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: var(--rouge);
    color: white;
    border: none;
    border-radius: 40px;
    font-size: 14px;
    font-weight: 600;
    font-family: var(--poppins);
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
    margin-bottom: 24px;
}

.btn-resend:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(204, 0, 0, 0.35);
}

.btn-resend:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.logout-link {
    display: block;
    color: var(--texte-doux);
    font-size: 14px;
    text-decoration: none;
    transition: color 0.25s ease;
}

.logout-link:hover {
    color: var(--rouge);
}

.success-message {
    background: var(--vert-bg);
    color: var(--vert-doux);
    padding: 12px 20px;
    border-radius: var(--radius);
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 24px;
}

@media (max-width: 480px) {
    .verify-card {
        padding: 32px 24px;
    }
}
</style>
@endpush

@section('content')
<div class="verify-page">
    <div class="verify-container">
        <div class="verify-card">
            <div class="verify-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                </svg>
            </div>

            <h1 class="verify-title">Verifiez votre adresse email</h1>

            <p class="verify-text">
                Un email de verification a ete envoye a <strong>{{ Auth::user()->email }}</strong>.
                Veuillez cliquer sur le lien dans l'email pour activer votre compte.
            </p>

            @if(session('resent'))
                <div class="success-message">
                    Un nouvel email de verification a ete envoye. Veuillez verifier votre boite de reception.
                </div>
            @endif

            <form action="{{ route('verification.resend') }}" method="POST">
                @csrf
                <button type="submit" class="btn-resend">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Renvoyer l'email de verification
                </button>
            </form>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-link">
                    Deconnectez-vous et reconnetez-vous avec un autre compte
                </button>
            </form>
        </div>
    </div>
</div>
@endsection