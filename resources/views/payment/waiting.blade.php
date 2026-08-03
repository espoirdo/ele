@extends('layouts.app')

@section('title', 'En attente de confirmation - ELEDJI')

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
        width: 60px;
        height: 60px;
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
        line-height: 1.7;
        margin-bottom: 28px;
    }

    .status-message a {
        color: var(--rouge);
        text-decoration: none;
        font-weight: 600;
    }

    .status-message a:hover {
        text-decoration: underline;
    }

    .booking-summary {
        background: #F9F9F9;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 20px;
        text-align: left;
    }

    .booking-summary p {
        font-size: 13px;
        color: #444;
        margin: 0 0 6px 0;
    }

    .booking-summary p:last-child {
        margin-bottom: 0;
    }

    .booking-summary .amount {
        font-size: 15px;
        font-weight: 700;
        color: var(--rouge);
        margin-top: 6px !important;
    }

    .reference {
        font-size: 11px;
        color: #AAAAAA;
    }

    .expired-message {
        display: none;
        margin-top: 20px;
        padding: 16px;
        background: #FFF5F5;
        border-radius: 10px;
        border: 1px solid #FFCCCC;
    }

    .expired-message p {
        font-size: 13px;
        color: var(--rouge);
        margin: 0 0 10px 0;
    }
</style>
@endpush

@section('content')
<div class="waiting-page">
    <div class="waiting-container">

        <div id="spinner" class="loading-spinner"></div>

        <h2 class="waiting-title">En attente de confirmation</h2>

        <p id="msg" class="status-message">
            @if($booking->moyen_paiement === 'tmoney')
                Une demande de paiement T-Money a été envoyée sur votre téléphone.<br>
                <strong>Confirmez la transaction sur votre téléphone Togocel.</strong>
            @else
                Une demande de paiement Flooz a été envoyée sur votre téléphone.<br>
                <strong>Confirmez la transaction sur votre téléphone Moov Africa.</strong>
            @endif
        </p>

        <div class="booking-summary">
            <p><strong>Événement :</strong> {{ $booking->event->titre }}</p>
            <p><strong>Billet :</strong> {{ ucfirst($booking->type_billet) }}</p>
            <p class="amount">{{ number_format($booking->total, 0, ',', ' ') }} FCFA</p>
        </div>

        <p class="reference">Réf : {{ $booking->paygate_identifier }}</p>

        <div id="expired-msg" class="expired-message">
            <p>Le délai de confirmation est dépassé.</p>
            <a href="{{ route('events.show', $booking->event->slug) }}">Retour à l'événement →</a>
        </div>

    </div>
</div>

@push('scripts')
<script>
const checkUrl  = '{{ route('payment.status', $booking->id) }}';
let   attempts  = 0;
const maxChecks = 36; // 3 minutes

const timer = setInterval(async () => {
    attempts++;

    if (attempts >= maxChecks) {
        clearInterval(timer);
        document.getElementById('spinner').style.display   = 'none';
        document.getElementById('msg').style.display       = 'none';
        document.getElementById('expired-msg').style.display = 'block';
        return;
    }

    try {
        const res  = await fetch(checkUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json();

        if (data.statut === 'confirmee' && data.redirect) {
            clearInterval(timer);
            window.location.href = data.redirect;
        } else if (data.statut === 'annulee' || data.statut === 'annule') {
            clearInterval(timer);
            document.getElementById('msg').innerHTML =
                'Le paiement a été annulé ou a expiré. <a href="{{ route('events.show', $booking->event->slug) }}" style="color:#CC0000;">Retour à l\'événement</a>';
            document.getElementById('spinner').style.display = 'none';
        }
    } catch(e) {
        console.error('Erreur polling:', e);
    }
}, 5000);
</script>
@endpush
@endsection
