@extends('layouts.app')

@section('title', 'Paiement en cours - ELEDJI')

@push('styles')
<style>
    :root {
        --rouge: #CC0000;
        --rouge-dark: #910000;
        --gris-bg: #F9F9F9;
        --texte: #1a1a1a;
        --texte-doux: #666;
        --poppins: 'Poppins', sans-serif;
    }
    *, *::before, *::after { box-sizing: border-box; }

    .waiting-page {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 120px 24px 48px;
        font-family: var(--poppins);
    }

    .waiting-container {
        max-width: 480px;
        width: 100%;
        text-align: center;
    }

    .loading-spinner {
        width: 64px;
        height: 64px;
        border: 4px solid #F0F0F0;
        border-top: 4px solid var(--rouge);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 24px;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .waiting-title {
        font-family: 'Eras Medium ITC', serif;
        font-size: 22px;
        color: var(--texte);
        margin-bottom: 12px;
    }

    .status-message {
        font-size: 14px;
        color: var(--texte-doux);
        line-height: 1.6;
        margin-bottom: 32px;
    }

    .status-message a {
        color: var(--rouge);
        text-decoration: none;
    }

    .status-message a:hover {
        text-decoration: underline;
    }

    .booking-summary {
        background: #F9F9F9;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        text-align: left;
    }

    .booking-summary p {
        font-size: 13px;
        color: #444;
        margin: 0 0 8px 0;
    }

    .booking-summary p:last-child {
        margin-bottom: 0;
    }

    .booking-summary .amount {
        color: var(--rouge);
        font-weight: 700;
    }

    .reference {
        font-size: 12px;
        color: #AAAAAA;
    }
</style>
@endpush

@section('content')
<div class="waiting-page">
    <div class="waiting-container">

        {{-- Animation de chargement --}}
        <div id="loading-spinner" class="loading-spinner"></div>

        <h2 class="waiting-title">Paiement en cours...</h2>

        <p id="status-message" class="status-message">
            @if($booking->moyen_paiement === 'tmoney')
                Confirmez la transaction sur votre téléphone Togocel (T-Money).
            @elseif($booking->moyen_paiement === 'flooz')
                Confirmez la transaction sur votre téléphone Moov Africa (Flooz).
            @else
                Votre paiement carte est en cours de vérification.
            @endif
        </p>

        <div class="booking-summary">
            <p><strong>Événement :</strong> {{ $booking->event->titre }}</p>
            <p><strong>Type de billet :</strong> {{ ucfirst($booking->type_billet) }}</p>
            <p class="amount"><strong>Montant :</strong> {{ number_format($booking->total, 0, ',', ' ') }} FCFA</p>
        </div>

        <p class="reference">Référence : {{ $booking->pzgate_reference }}</p>
    </div>
</div>

@push('scripts')
<script>
const bookingId  = {{ $booking->id }};
const checkUrl   = '/paiement/statut/' + bookingId;
let   checkCount = 0;
const maxChecks  = 36; // 3 minutes maximum

const interval = setInterval(async () => {
    checkCount++;

    if (checkCount >= maxChecks) {
        clearInterval(interval);
        document.getElementById('status-message').innerHTML =
            'Le délai d\'attente est dépassé. <a href="/mes-reservations" style="color:#CC0000;">Voir mes réservations</a>';
        document.getElementById('loading-spinner').style.display = 'none';
        return;
    }

    try {
        const response = await fetch(checkUrl);
        const data     = await response.json();

        if (data.statut === 'confirmee') {
            clearInterval(interval);
            window.location.href = '/reservation/confirmation/' + bookingId;
        } else if (data.statut === 'annulee') {
            clearInterval(interval);
            document.getElementById('status-message').innerHTML =
                'Le paiement a échoué ou a été annulé. <a href="javascript:history.back()" style="color:#CC0000;">Réessayer</a>';
            document.getElementById('loading-spinner').style.display = 'none';
        }
    } catch (e) {
        console.error('Erreur vérification statut', e);
    }
}, 5000);
</script>
@endpush
@endsection
