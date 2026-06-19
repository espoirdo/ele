@extends('layouts.app')

@section('title', 'Confirmation - ELEDJI')

@push('styles')
<style>
:root {
    --rouge: #CC0000;
    --rouge-dark: #910000;
    --rose: #F7D6D3;
    --rose-pale: #FDF0F0;
    --gris-bg: #F9F9F9;
    --gris-border: #E0E0E0;
    --texte: #1a1a1a;
    --texte-doux: #666;
    --poppins: 'Poppins', sans-serif;
    --radius: 16px;
    --radius-sm: 12px;
    --shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
}
*, *::before, *::after { box-sizing: border-box; }

.confirmation-page {
    min-height: calc(100vh - 80px);
    padding: 48px 24px;
    background: var(--gris-bg);
    font-family: var(--poppins);
}

.confirmation-container {
    max-width: 560px;
    margin: 0 auto;
}

.confirmation-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 32px;
    text-align: center;
}

.confirmation-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 24px;
    background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.confirmation-icon svg {
    width: 40px;
    height: 40px;
    color: white;
}

.confirmation-title {
    font-family: 'Eras Medium ITC', serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 8px;
}

.confirmation-subtitle {
    font-size: 14px;
    color: var(--texte-doux);
    margin: 0 0 32px;
}

.confirmation-details {
    background: var(--gris-bg);
    border-radius: var(--radius-sm);
    padding: 20px;
    margin-bottom: 32px;
    text-align: left;
}

.confirmation-detail-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--gris-border);
}

.confirmation-detail-row:last-child {
    border-bottom: none;
}

.confirmation-detail-label {
    font-size: 13px;
    color: var(--texte-doux);
}

.confirmation-detail-value {
    font-size: 13px;
    font-weight: 600;
    color: var(--texte);
}

.confirmation-message {
    background: var(--rose-pale);
    border-radius: var(--radius-sm);
    padding: 16px;
    margin-bottom: 32px;
}

.confirmation-message p {
    font-size: 13px;
    color: var(--texte);
    margin: 0;
    line-height: 1.6;
}

.confirmation-message strong {
    color: var(--rouge);
}

.confirmation-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.btn-retour {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 28px;
    background: white;
    color: var(--rouge);
    border: 2px solid var(--rouge);
    border-radius: 40px;
    font-size: 14px;
    font-weight: 600;
    font-family: var(--poppins);
    text-decoration: none;
    transition: all 0.25s ease;
    cursor: pointer;
}

.btn-retour:hover {
    background: var(--rose-pale);
}

.btn-mes-reservations {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 28px;
    background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
    color: white;
    border: none;
    border-radius: 40px;
    font-size: 14px;
    font-weight: 600;
    font-family: var(--poppins);
    text-decoration: none;
    transition: all 0.25s ease;
}

.btn-mes-reservations:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(204, 0, 0, 0.35);
}

@media (max-width: 480px) {
    .confirmation-card {
        padding: 24px 20px;
    }
}
</style>
@endpush

@section('content')
<div class="confirmation-page">
    <div class="confirmation-container">
        <div class="confirmation-card">

            <div class="confirmation-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h1 class="confirmation-title">Reservation en cours de traitement</h1>
            <p class="confirmation-subtitle">Votre demande a ete enregistree</p>

            <div class="confirmation-details">
                <div class="confirmation-detail-row">
                    <span class="confirmation-detail-label">Numero de reservation</span>
                    <span class="confirmation-detail-value">#{{ $booking->id }}</span>
                </div>
                <div class="confirmation-detail-row">
                    <span class="confirmation-detail-label">Evenement</span>
                    <span class="confirmation-detail-value">{{ $event->titre }}</span>
                </div>
                <div class="confirmation-detail-row">
                    <span class="confirmation-detail-label">Date</span>
                    <span class="confirmation-detail-value">{{ $event->date->translatedFormat('d M Y') }}</span>
                </div>
                <div class="confirmation-detail-row">
                    <span class="confirmation-detail-label">Montant</span>
                    <span class="confirmation-detail-value" style="color: var(--rouge); font-weight: 700;">
                        {{ number_format($booking->total, 0, ',', ' ') }} XOF
                    </span>
                </div>
                <div class="confirmation-detail-row">
                    <span class="confirmation-detail-label">Moyen de paiement</span>
                    <span class="confirmation-detail-value">
                        @switch($methode)
                            @case('tmoney') T-Money @break
                            @case('flooz') Flooz @break
                            @case('carte') Carte bancaire @break
                            @default Carte
                        @endswitch
                    </span>
                </div>
            </div>

            <div class="confirmation-message">
                @switch($methode)
                    @case('tmoney')
                        <p>Confirmez la transaction sur votre telephone au <strong>{{ $telephone }}</strong></p>
                    @break
                    @case('flooz')
                        <p>Confirmez la transaction sur votre telephone au <strong>{{ $telephone }}</strong></p>
                    @break
                    @case('carte')
                        <p>Votre paiement est en cours de verification.</p>
                    @break
                    @default
                        <p>Votre paiement est en cours de verification.</p>
                @endswitch
            </div>

            <div class="confirmation-actions">
                <a href="{{ route('events.show', $event->slug) }}" class="btn-retour">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour a l'evenement
                </a>
                <a href="{{ route('home') }}" class="btn-mes-reservations">
                    Mes reservations
                </a>
            </div>

        </div>
    </div>
</div>
@endsection