@extends('layouts.app')

@section('title', 'Confirmation - ELEDJI')

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

.success-page {
    min-height: calc(100vh - 80px);
    padding: 48px 24px;
    background: var(--gris-bg);
    font-family: var(--poppins);
}

.success-container {
    max-width: 520px;
    margin: 0 auto;
}

.success-message {
    background: var(--vert-bg);
    color: var(--vert-doux);
    padding: 14px 20px;
    border-radius: var(--radius);
    text-align: center;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 24px;
}

.ticket-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.ticket-header {
    background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.ticket-logo {
    font-family: 'Eras Medium ITC', serif;
    font-size: 18px;
    font-weight: 700;
    color: white;
}

.ticket-type {
    font-size: 12px;
    font-weight: 600;
    color: white;
    opacity: 0.9;
}

.ticket-body {
    padding: 24px;
}

.ticket-event-title {
    font-family: 'Eras Medium ITC', serif;
    font-size: 20px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 16px;
    text-align: center;
}

.ticket-details {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
}

.ticket-detail-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.ticket-detail-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--gris-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.ticket-detail-icon svg {
    width: 16px;
    height: 16px;
}

.ticket-detail-info {
    flex: 1;
}

.ticket-detail-label {
    font-size: 11px;
    color: var(--texte-doux);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 2px;
}

.ticket-detail-value {
    font-size: 14px;
    font-weight: 600;
    color: var(--texte);
}

.ticket-detail-value.vert {
    color: var(--vert-doux);
}

.ticket-divider {
    border: none;
    border-top: 2px dashed var(--gris-border);
    margin: 20px 0;
}

.ticket-number {
    text-align: center;
    padding: 8px 0;
}

.ticket-number-label {
    font-size: 11px;
    color: var(--texte-doux);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 4px;
}

.ticket-number-value {
    font-family: 'Poppins', monospace;
    font-size: 16px;
    font-weight: 700;
    color: var(--texte);
    letter-spacing: 0.1em;
}

.ticket-footer {
    padding: 12px 24px;
    background: var(--gris-bg);
    text-align: center;
    font-size: 11px;
    color: var(--texte-doux);
}

.success-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}

.btn-outline {
    flex: 1;
    padding: 14px;
    background: white;
    color: var(--rouge);
    border: 2px solid var(--rouge);
    border-radius: 40px;
    font-size: 14px;
    font-weight: 600;
    font-family: var(--poppins);
    text-decoration: none;
    text-align: center;
    transition: all 0.25s ease;
    cursor: pointer;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

.btn-outline:hover {
    background: var(--vert-bg);
}

.btn-primary {
    flex: 1;
    padding: 14px;
    background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
    color: white;
    border: none;
    border-radius: 40px;
    font-size: 14px;
    font-weight: 600;
    font-family: var(--poppins);
    text-decoration: none;
    text-align: center;
    transition: all 0.25s ease;
    cursor: pointer;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(204, 0, 0, 0.35);
}

@media (max-width: 480px) {
    .success-container {
        padding: 0 12px;
    }

    .ticket-header {
        padding: 14px 20px;
    }

    .ticket-body {
        padding: 20px;
    }
}
</style>
@endpush

@section('content')
<div class="success-page">
    <div class="success-container">

        <div class="success-message">
            Votre place est confirmee. Un recapitulatif vous a ete envoye.
        </div>

        <div class="ticket-card">
            <div class="ticket-header">
                <span class="ticket-logo">Eledji</span>
                <span class="ticket-type">Billet de participation</span>
            </div>

            <div class="ticket-body">
                <h1 class="ticket-event-title">{{ $event->titre }}</h1>

                <div class="ticket-details">
                    <div class="ticket-detail-row">
                        <div class="ticket-detail-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="#007AFF" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="ticket-detail-info">
                            <div class="ticket-detail-label">Date</div>
                            <div class="ticket-detail-value">{{ $event->date->translatedFormat('l d F Y') }}</div>
                        </div>
                    </div>

                    <div class="ticket-detail-row">
                        <div class="ticket-detail-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="#FF9500" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ticket-detail-info">
                            <div class="ticket-detail-label">Heure</div>
                            <div class="ticket-detail-value">{{ $event->heure }}</div>
                        </div>
                    </div>

                    <div class="ticket-detail-row">
                        <div class="ticket-detail-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="#34C759" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                        </div>
                        <div class="ticket-detail-info">
                            <div class="ticket-detail-label">Lieu</div>
                            <div class="ticket-detail-value">{{ $event->lieu }}</div>
                        </div>
                    </div>

                    <div class="ticket-detail-row">
                        <div class="ticket-detail-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="#34C759" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ticket-detail-info">
                            <div class="ticket-detail-label">Type</div>
                            <div class="ticket-detail-value vert">Entree gratuite</div>
                        </div>
                    </div>

                    <div class="ticket-detail-row">
                        <div class="ticket-detail-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="#CC0000" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="ticket-detail-info">
                            <div class="ticket-detail-label">Places reservees</div>
                            <div class="ticket-detail-value">{{ $booking->nb_places }} place(s)</div>
                        </div>
                    </div>
                </div>

                <hr class="ticket-divider">

                <div class="ticket-number">
                    <div class="ticket-number-label">Numero de reservation</div>
                    <div class="ticket-number-value">{{ $booking->numero_reservation }}</div>
                </div>
            </div>

            <div class="ticket-footer">
                Paiement 100% securise via Eledji
            </div>
        </div>

        <div class="success-actions">
            @if($booking->status === 'confirmee' && $booking->ticket_path)
                <a href="{{ route('ticket.download', $booking) }}" class="btn-primary" download>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display: inline; margin-right: 6px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Telecharger mon ticket
                </a>
            @endif
            <a href="{{ route('home') }}" class="btn-outline">
                Retour a l'accueil
            </a>
            <a href="{{ route('user.bookings') }}" class="btn-primary">
                Voir mes reservations
            </a>
        </div>

    </div>
</div>
@endsection