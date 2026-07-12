@extends('layouts.app')

@section('title', 'Devenir VIP - ELEDJI')

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
    --or: #F5A623;
    --or-bg: #FFFBF0;
}
*, *::before, *::after { box-sizing: border-box; }

.vip-page {
    min-height: calc(100vh - 80px);
    padding: 48px 24px;
    background: linear-gradient(180deg, var(--or-bg) 0%, var(--gris-bg) 100%);
    font-family: var(--poppins);
}

.vip-container {
    max-width: 560px;
    margin: 0 auto;
}

.vip-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 32px;
    border: 2px solid var(--or);
}

.vip-header {
    text-align: center;
    margin-bottom: 32px;
}

.vip-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--or);
    color: white;
    padding: 8px 16px;
    border-radius: 24px;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 16px;
}

.vip-title {
    font-family: 'Eras Medium ITC', serif;
    font-size: 26px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 8px;
}

.vip-price {
    font-size: 42px;
    font-weight: 800;
    color: var(--rouge);
    margin-bottom: 4px;
}

.vip-price span {
    font-size: 14px;
    font-weight: 500;
    color: var(--texte-doux);
}

.vip-duration {
    font-size: 14px;
    color: var(--texte-doux);
}

.vip-advantages {
    background: var(--rose-pale);
    border-radius: var(--radius-sm);
    padding: 20px;
    margin-bottom: 24px;
}

.vip-advantages-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--texte-doux);
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.vip-advantage {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
    font-size: 14px;
    color: var(--texte);
}

.vip-advantage:last-child {
    margin-bottom: 0;
}

.vip-advantage svg {
    width: 20px;
    height: 20px;
    color: var(--rouge);
    flex-shrink: 0;
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
    .vip-card {
        padding: 24px 20px;
    }

    .vip-title {
        font-size: 22px;
    }

    .vip-price {
        font-size: 36px;
    }
}
</style>
@endpush

@section('content')
<div class="vip-page">
    <div class="vip-container">
        <div class="vip-card" x-data="{ methode: '' }">

            <div class="vip-header">
                <div class="vip-badge">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    VIP
                </div>
                <h1 class="vip-title">{{ $vipPageTitle }}</h1>
                <p class="vip-price">{{ number_format($vipPrice, 0, ',', ' ') }} XOF <span>/ {{ $vipDuration }} jours</span></p>
                <p class="vip-duration">Abonnement renewed automatiquement</p>
            </div>

            <div class="vip-advantages">
                <p class="vip-advantages-title">Vos avantages</p>
                <div class="vip-advantage">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Accès exclusif à la Marketplace
                </div>
                <div class="vip-advantage">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Badge VIP visible sur votre profil
                </div>
                <div class="vip-advantage">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Mise en avant prioritaire de vos événements
                </div>
                <div class="vip-advantage">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Accès anticipé aux événements exclusifs
                </div>
            </div>

            <form action="{{ route('vip.subscribe.process') }}" method="POST">
                @csrf

                <h2 class="payment-section-title">Choisissez votre moyen de paiement</h2>

                {{-- TMoney --}}
                <div class="payment-method-card"
                     :class="methode === 'tmoney' ? 'selected' : ''"
                     @click="methode = 'tmoney'">
                    <div class="payment-method-icon">
                        @if(setting('logo_tmoney'))
                            <img src="{{ Storage::url(setting('logo_tmoney')) }}" alt="T-Money" style="width: 32px; height: 32px; object-fit: contain;">
                        @else
                            TM
                        @endif
                    </div>
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
                    <div class="payment-method-icon">
                        @if(setting('logo_flooz'))
                            <img src="{{ Storage::url(setting('logo_flooz')) }}" alt="Flooz" style="width: 32px; height: 32px; object-fit: contain;">
                        @else
                            FL
                        @endif
                    </div>
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
                        @if(setting('logo_carte_bancaire'))
                            <img src="{{ Storage::url(setting('logo_carte_bancaire')) }}" alt="Carte bancaire" style="width: 32px; height: 32px; object-fit: contain;">
                        @else
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        @endif
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
                               placeholder="Nom apparaître sur la carte"
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