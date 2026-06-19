@extends('layouts.app')

@section('title', 'Choisir un billet - ELEDJI')

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

.ticket-selection-page {
    min-height: calc(100vh - 80px);
    padding: 48px 24px;
    background: var(--gris-bg);
    font-family: var(--poppins);
}

.ticket-selection-container {
    max-width: 600px;
    margin: 0 auto;
}

.ticket-selection-header {
    text-align: center;
    margin-bottom: 32px;
}

.ticket-selection-title {
    font-family: 'Eras Medium ITC', serif;
    font-size: 28px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 8px;
}

.ticket-selection-subtitle {
    font-size: 14px;
    color: var(--texte-doux);
}

.ticket-grid {
    display: grid;
    gap: 16px;
    margin-bottom: 24px;
}

.ticket-option {
    background: white;
    border: 2px solid var(--gris-border);
    border-radius: var(--radius-sm);
    padding: 20px;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 16px;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

.ticket-option:hover {
    border-color: #ccc;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.ticket-option.selected {
    border-color: var(--rouge);
    background: var(--rose-pale);
}

.ticket-option-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: var(--gris-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.ticket-option.selected .ticket-option-icon {
    background: var(--rouge);
    color: white;
}

.ticket-option-info {
    flex: 1;
}

.ticket-option-name {
    font-size: 16px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 4px;
}

.ticket-option-desc {
    font-size: 12px;
    color: var(--texte-doux);
    margin: 0;
}

.ticket-option-price {
    font-size: 18px;
    font-weight: 700;
    color: var(--rouge);
}

.ticket-option-radio {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 2px solid var(--gris-border);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.25s ease;
}

.ticket-option.selected .ticket-option-radio {
    border-color: var(--rouge);
    background: var(--rouge);
}

.ticket-option-radio::after {
    content: '✓';
    color: white;
    font-size: 14px;
    font-weight: 700;
    opacity: 0;
    transition: opacity 0.25s ease;
}

.ticket-option.selected .ticket-option-radio::after {
    opacity: 1;
}

.btn-continue {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
    color: white;
    border: none;
    border-radius: 40px;
    font-size: 15px;
    font-weight: 600;
    font-family: var(--poppins);
    cursor: pointer;
    transition: all 0.25s ease;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

.btn-continue:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(204, 0, 0, 0.35);
}

.btn-continue:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}
</style>
@endpush

<div class="ticket-selection-page">
    <div class="ticket-selection-container">
        <!-- Header -->
        <div class="ticket-selection-header">
            <h1 class="ticket-selection-title">Choisir un billet</h1>
            <p class="ticket-selection-subtitle">{{ $event->titre }}</p>
        </div>

        <!-- Tickets Grid -->
        <form method="GET" action="{{ route('payment.show', $event->slug) }}" id="ticketForm">
            <div class="ticket-grid">
                @forelse($event->tickets as $ticket)
                    <label class="ticket-option" data-ticket-id="{{ $ticket->id }}">
                        <div class="ticket-option-icon">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                        <div class="ticket-option-info">
                            <div class="ticket-option-name">{{ $ticket->nom }}</div>
                            <div class="ticket-option-desc">{{ $ticket->disponibles }} place(s) disponible(s)</div>
                        </div>
                        <div class="ticket-option-price">{{ number_format($ticket->prix, 0, ',', ' ') }} XOF</div>
                        <input type="radio" name="ticket_id" value="{{ $ticket->id }}" style="display: none;">
                        <div class="ticket-option-radio"></div>
                    </label>
                @empty
                    <p style="text-align: center; color: var(--texte-doux);">
                        Aucun billet disponible
                    </p>
                @endforelse
            </div>

            <button type="submit" class="btn-continue" id="continueBtn" disabled>
                Continuer vers le paiement
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const ticketOptions = document.querySelectorAll('.ticket-option');
    const continueBtn = document.getElementById('continueBtn');
    const ticketForm = document.getElementById('ticketForm');

    ticketOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove selected class from all
            ticketOptions.forEach(o => o.classList.remove('selected'));
            
            // Add selected class to clicked
            this.classList.add('selected');
            
            // Check the radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Enable button
            continueBtn.disabled = false;
        });
    });

    ticketForm.addEventListener('submit', function(e) {
        const selectedTicket = document.querySelector('input[name="ticket_id"]:checked');
        if (!selectedTicket) {
            e.preventDefault();
        }
    });
</script>
@endpush

@endsection
