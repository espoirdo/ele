@extends('layouts.app')

@section('title', 'Paiement - ELEDJI')

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

.payment-page {
    min-height: calc(100vh - 80px);
    padding: 48px 24px;
    background: var(--gris-bg);
    font-family: var(--poppins);
}

.payment-container {
    max-width: 560px;
    margin: 0 auto;
}

.payment-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 32px;
}

.payment-header {
    text-align: center;
    margin-bottom: 32px;
}

.payment-event-summary {
    display: flex;
    gap: 16px;
    align-items: center;
    padding: 16px;
    background: var(--gris-bg);
    border-radius: var(--radius-sm);
    margin-bottom: 24px;
}

.payment-event-img {
    width: 80px;
    height: 60px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
}

.payment-event-info {
    flex: 1;
    min-width: 0;
}

.payment-event-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.payment-event-meta {
    font-size: 12px;
    color: var(--texte-doux);
}

.payment-total {
    text-align: center;
    padding: 16px;
    margin-bottom: 24px;
}

.payment-total-label {
    font-size: 13px;
    color: var(--texte-doux);
    margin-bottom: 4px;
}

.payment-total-amount {
    font-size: 32px;
    font-weight: 800;
    color: var(--rouge);
}

.payment-section-title {
    font-family: 'Eras Medium ITC', serif;
    font-size: 18px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 16px;
    text-align: center;
}

.payment-method-card {
    border: 2px solid var(--gris-border);
    border-radius: var(--radius-sm);
    padding: 20px;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 12px;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

.payment-method-card:hover {
    border-color: #ccc;
}

.payment-method-card.selected {
    border-color: var(--rouge);
    background: var(--rose-pale);
}

.payment-method-card.selected.flooz {
    border-color: #1A237E;
    background: #E8EAF6;
}

.payment-method-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    flex-shrink: 0;
}

.payment-method-card.tmoney .payment-method-icon {
    background: var(--rouge);
    color: white;
}

.payment-method-card.flooz .payment-method-icon {
    background: #1A237E;
    color: white;
}

.payment-method-card.carte .payment-method-icon {
    background: var(--gris-bg);
    color: var(--texte);
}

.payment-method-info {
    flex: 1;
}

.payment-method-name {
    font-size: 15px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 2px;
}

.payment-method-desc {
    font-size: 12px;
    color: var(--texte-doux);
    margin: 0;
}

.payment-method-radio {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid var(--gris-border);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.payment-method-card.selected .payment-method-radio {
    border-color: var(--rouge);
    background: var(--rouge);
}

.payment-method-card.selected.flooz .payment-method-radio {
    border-color: #1A237E;
    background: #1A237E;
}

.payment-method-card.selected .payment-method-radio::after {
    content: '';
    width: 8px;
    height: 8px;
    background: white;
    border-radius: 50%;
}

.payment-form {
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid var(--gris-border);
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--texte);
    margin-bottom: 6px;
}

.form-group input {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid var(--gris-border);
    border-radius: 8px;
    font-size: 14px;
    font-family: var(--poppins);
    color: var(--texte);
    transition: border-color 0.25s, box-shadow 0.25s;
    outline: none;
}

.form-group input:focus {
    border-color: var(--rouge);
    box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.1);
}

.form-group input::placeholder {
    color: #999;
}

.phone-input-wrapper {
    display: flex;
    align-items: center;
}

.phone-prefix {
    padding: 12px 14px;
    background: var(--gris-bg);
    border: 1.5px solid var(--gris-border);
    border-right: none;
    border-radius: 8px 0 0 8px;
    font-size: 14px;
    color: var(--texte-doux);
}

.phone-input-wrapper input {
    border-radius: 0 8px 8px 0;
    flex: 1;
}

.form-hint {
    font-size: 12px;
    color: var(--texte-doux);
    margin-top: 8px;
}

.places-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--rouge);
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 700;
    transition: all 0.25s ease;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

.places-btn:hover:not(:disabled) {
    background: var(--rouge-dark);
    transform: scale(1.05);
}

.places-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
    opacity: 0.6;
}

.btn-confirmer {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
    color: white;
    border: none;
    border-radius: 40px;
    font-size: 15px;
    font-weight: 700;
    font-family: var(--poppins);
    cursor: pointer;
    transition: all 0.25s ease;
    margin-top: 24px;
}

.btn-confirmer:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(204, 0, 0, 0.35);
}

.btn-confirmer:active {
    transform: scale(0.98);
}

@media (max-width: 480px) {
    .payment-card {
        padding: 24px 20px;
    }

    .payment-event-summary {
        flex-direction: column;
        text-align: center;
    }

    .payment-event-img {
        width: 100%;
        height: 120px;
    }
}
</style>
@endpush

@section('content')
<div class="payment-page">
    <div class="payment-container">
        <div class="payment-card" x-data="{ methode: '' }">

            <div class="payment-header">
                <h1 style="font-family: 'Eras Medium ITC', serif; font-size: 22px; color: var(--texte); margin: 0 0 8px;">
                    Paiement
                </h1>
            </div>

            <div class="payment-event-summary">
                @if($event->image_couverture)
                    <img src="{{ Storage::url($event->image_couverture) }}"
                         alt="{{ $event->titre }}"
                         class="payment-event-img">
                @else
                    <div class="payment-event-img" style="background: linear-gradient(135deg, var(--rouge), var(--rouge-dark)); display: flex; align-items: center; justify-content: center;">
                        <span style="color: white; font-weight: 700; font-size: 18px;">ELEDJI</span>
                    </div>
                @endif
                <div class="payment-event-info">
                    <h3 class="payment-event-title">{{ $event->titre }}</h3>
                    <p class="payment-event-meta">
                        {{ $event->date->translatedFormat('d M Y') }} - {{ $event->lieu }}
                    </p>
                </div>
            </div>

            <div class="payment-total">
                <p class="payment-total-label">Total a payer</p>
                <p class="payment-total-amount">{{ number_format($price ?? $total, 0, ',', ' ') }} XOF</p>
            </div>

            <form action="{{ route('payment.process', $event->slug) }}" method="POST">
                @csrf

                <h2 class="payment-section-title">Choisissez votre moyen de paiement</h2>

                {{-- TMoney --}}
                <div class="payment-method-card"
                     :class="methode === 'tmoney' ? 'selected' : ''"
                     @click="methode = 'tmoney'">
                    <div class="payment-method-icon">TM</div>
                    <div class="payment-method-info">
                        <p class="payment-method-name" style="color: var(--rouge);">T-Money</p>
                        <p class="payment-method-desc">Paiement mobile Togocel</p>
                    </div>
                    <div class="payment-method-radio"></div>
                </div>
                <input type="radio" name="methode" value="tmoney" x-model="methode" style="display: none;">

                {{-- Flooz --}}
                <div class="payment-method-card flooz"
                     :class="methode === 'flooz' ? 'selected flooz' : ''"
                     @click="methode = 'flooz'">
                    <div class="payment-method-icon">FL</div>
                    <div class="payment-method-info">
                        <p class="payment-method-name" style="color: #1A237E;">Flooz</p>
                        <p class="payment-method-desc">Paiement mobile Moov Africa</p>
                    </div>
                    <div class="payment-method-radio"></div>
                </div>
                <input type="radio" name="methode" value="flooz" x-model="methode" style="display: none;">

                {{-- Carte bancaire --}}
                <div class="payment-method-card carte"
                     :class="methode === 'carte' ? 'selected' : ''"
                     @click="methode = 'carte'">
                    <div class="payment-method-icon">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div class="payment-method-info">
                        <p class="payment-method-name">Carte bancaire</p>
                        <p class="payment-method-desc">Visa, Mastercard, other</p>
                    </div>
                    <div class="payment-method-radio"></div>
                </div>
                <input type="radio" name="methode" value="carte" x-model="methode" style="display: none;">

                {{-- Formulaire TMoney/Flooz --}}
                <div class="payment-form" x-show="methode === 'tmoney' || methode === 'flooz'" x-transition>
                    <div class="form-group">
                        <label for="telephone">Numéro de téléphone</label>
                        <div class="phone-input-wrapper">
                            <span class="phone-prefix">+228</span>
                            <input type="tel"
                                   id="telephone"
                                   name="telephone"
                                   placeholder="XX XX XX XX"
                                   pattern="[0-9]{8}"
                                   maxlength="8">
                        </div>
                        <p class="form-hint">Vous allez recevoir une demande de confirmation sur votre téléphone.</p>
                    </div>

                    <button type="submit" class="btn-confirmer">
                        Procéder au paiement
                    </button>
                </div>

                {{-- Formulaire Carte bancaire --}}
                <div class="payment-form" x-show="methode === 'carte'" x-transition>
                    <div class="form-group">
                        <label for="numero_carte">Numéro de carte</label>
                        <input type="text"
                               id="numero_carte"
                               name="numero_carte"
                               placeholder="XXXX XXXX XXXX XXXX"
                               maxlength="19"
                               autocomplete="off">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label for="expiration">Date d'expiration</label>
                            <input type="text"
                                   id="expiration"
                                   name="expiration"
                                   placeholder="MM/AA"
                                   maxlength="5"
                                   autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text"
                                   id="cvv"
                                   name="cvv"
                                   placeholder="XXX"
                                   maxlength="3"
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nom_titulaire">Nom du titulaire</label>
                        <input type="text"
                               id="nom_titulaire"
                               name="nom_titulaire"
                               placeholder="Nom appearant sur la carte"
                               autocomplete="off">
                    </div>

                    <button type="submit" class="btn-confirmer">
                        Procéder au paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('numero_carte')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '');
    let formatted = value.match(/.{1,4}/g)?.join(' ') || '';
    e.target.value = formatted;
});
</script>
@endpush
@endsection