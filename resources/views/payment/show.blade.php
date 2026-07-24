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
    padding: 18px 20px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 12px;
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
    border-color: #1565C0;
    background: #E3F2FD;
}

.payment-method-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 13px;
    flex-shrink: 0;
}

.payment-method-card.tmoney .payment-method-icon {
    background: #E8F5E9;
    color: #2E7D32;
}

.payment-method-card.flooz .payment-method-icon {
    background: #E3F2FD;
    color: #1565C0;
}

.payment-method-info {
    flex: 1;
}

.payment-method-name {
    font-size: 14px;
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
    border-color: #1565C0;
    background: #1565C0;
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
    font-weight: 500;
    color: #444;
    margin-bottom: 6px;
}

.phone-input-wrapper {
    display: flex;
    align-items: center;
    border: 1.5px solid #E0E0E0;
    border-radius: 8px;
    overflow: hidden;
}

.phone-prefix {
    padding: 12px 14px;
    background: #F5F5F5;
    font-size: 14px;
    color: #444;
    border-right: 1px solid #E0E0E0;
    white-space: nowrap;
}

.phone-input-wrapper input {
    flex: 1;
    border: none;
    outline: none;
    padding: 12px 14px;
    font-size: 14px;
    font-family: var(--poppins);
    color: var(--texte);
}

.form-hint {
    font-size: 11px;
    color: var(--texte-doux);
    margin-top: 6px;
}

.btn-confirmer {
    width: 100%;
    background: linear-gradient(to right, var(--rouge), var(--rouge-dark));
    color: white;
    border: none;
    border-radius: 40px;
    padding: 14px;
    font-family: var(--poppins);
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    outline: none;
    transition: all 0.25s ease;
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
        <div class="payment-card" x-data="{ methode: '', telephone: '' }">

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

                {{-- Type de billet sélectionné (hidden) --}}
                <input type="hidden" name="type_billet" value="{{ $typeBillet }}">

                <h2 class="payment-section-title">Choisissez votre moyen de paiement</h2>

                {{-- TMoney --}}
                <div class="payment-method-card tmoney"
                     :class="methode === 'tmoney' ? 'selected' : ''"
                     @click="methode = 'tmoney'">
                    <div class="payment-method-icon">
                        <span>T</span>
                    </div>
                    <div class="payment-method-info">
                        <p class="payment-method-name" style="color: #2E7D32;">T-Money</p>
                        <p class="payment-method-desc">Paiement mobile Togocel</p>
                    </div>
                    <div class="payment-method-radio"></div>
                </div>
                <input type="radio" name="moyen_paiement" value="tmoney" x-model="methode" style="display: none;">

                {{-- Flooz --}}
                <div class="payment-method-card flooz"
                     :class="methode === 'flooz' ? 'selected flooz' : ''"
                     @click="methode = 'flooz'">
                    <div class="payment-method-icon">
                        <span>F</span>
                    </div>
                    <div class="payment-method-info">
                        <p class="payment-method-name" style="color: #1565C0;">Flooz</p>
                        <p class="payment-method-desc">Paiement mobile Moov Africa</p>
                    </div>
                    <div class="payment-method-radio"></div>
                </div>
                <input type="radio" name="moyen_paiement" value="flooz" x-model="methode" style="display: none;">

                {{-- Champ téléphone visible si une méthode est sélectionnée --}}
                <div x-show="methode !== ''" x-transition style="margin-bottom: 20px;">
                    <label style="display: block; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500; color: #444; margin-bottom: 6px;">
                        Numéro de téléphone
                    </label>
                    <div class="phone-input-wrapper">
                        <span class="phone-prefix">+228</span>
                        <input type="tel"
                               name="telephone"
                               x-model="telephone"
                               maxlength="8"
                               placeholder="XX XX XX XX"
                               style="oninput: this.value = this.value.replace(/[^0-9]/g, '').slice(0,8);">
                    </div>
                    <p class="form-hint">Vous recevrez une demande de confirmation sur ce numéro.</p>
                    @error('telephone')
                        <p style="color: #CC0000; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Bouton payer --}}
                <button type="submit" x-show="methode !== '' && telephone.length === 8"
                        style="width: 100%; background: linear-gradient(to right, #CC0000, #910000); color: white; border: none; border-radius: 40px; padding: 14px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 15px; cursor: pointer; outline: none; transition: all 0.25s ease;">
                    Confirmer le paiement — {{ number_format($price ?? $total, 0, ',', ' ') }} XOF
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
