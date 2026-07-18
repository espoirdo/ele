@extends('layouts.app')

@section('title', 'Creer un evenement - Etape 3 sur 4 - ELEDJI')

@section('content')
<div class="create-event-page" x-data="ticketManager()">
    <div class="create-event-container">
        {{-- Progress Bar --}}
        @include('events.create.progress-bar', ['currentStep' => 3])

        {{-- Header --}}
        <div class="create-event-header">
            <h1>Creer un evenement - Etape 3 sur 4</h1>
            <p>Types de billets</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('events.create.step3.post') }}" method="POST" class="create-event-form">
            @csrf

            <div class="form-card">
                <h3 class="card-title">Selectionnez les types de billets</h3>
                <p class="card-subtitle">Activez au moins un type de billet pour votre evenement</p>

                {{-- Error message for no ticket type selected --}}
                @error('billet_classique_actif')
                    <div class="error-alert">{{ $message }}</div>
                @enderror

                <div class="ticket-types-grid">
                    {{-- Classique --}}
                    <div class="ticket-type-card" :class="classiqueActive ? 'active' : ''" style="--accent-color: #333333;">
                        <div class="ticket-type-header">
                            <div class="ticket-type-badge" style="background: #333333;">Classique</div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="billet_classique_actif" x-model="classiqueActive">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="ticket-type-body" x-show="classiqueActive" x-transition>
                            <div class="form-group">
                                <label for="billet_classique_prix">Prix (FCFA)</label>
                                <input type="number"
                                       id="billet_classique_prix"
                                       name="billet_classique_prix"
                                       value="{{ old('billet_classique_prix', $data['billet_classique_prix'] ?? '') }}"
                                       min="0"
                                       step="100"
                                       placeholder="Prix en FCFA">
                                <small class="form-hint">Laissez 0 ou vide pour un billet gratuit</small>
                                <div class="gratuit-badge" x-show="parseFloat(classiquePrix) === 0 || classiquePrix === ''" x-transition>
                                    Gratuit
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- VIP --}}
                    <div class="ticket-type-card" :class="vipActive ? 'active' : ''" style="--accent-color: #CC0000;">
                        <div class="ticket-type-header">
                            <div class="ticket-type-badge" style="background: #CC0000;">VIP</div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="billet_vip_actif" x-model="vipActive">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="ticket-type-body" x-show="vipActive" x-transition>
                            <div class="form-group">
                                <label for="billet_vip_prix">Prix (FCFA)</label>
                                <input type="number"
                                       id="billet_vip_prix"
                                       name="billet_vip_prix"
                                       value="{{ old('billet_vip_prix', $data['billet_vip_prix'] ?? '') }}"
                                       min="0"
                                       step="100"
                                       placeholder="Prix en FCFA">
                                <small class="form-hint">Laissez 0 ou vide pour un billet gratuit</small>
                                <div class="gratuit-badge" x-show="parseFloat(vipPrix) === 0 || vipPrix === ''" x-transition>
                                    Gratuit
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- VVIP --}}
                    <div class="ticket-type-card" :class="vvipActive ? 'active' : ''" style="--accent-color: #F5A623;">
                        <div class="ticket-type-header">
                            <div class="ticket-type-badge" style="background: #F5A623;">VVIP</div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="billet_vvip_actif" x-model="vvipActive">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="ticket-type-body" x-show="vvipActive" x-transition>
                            <div class="form-group">
                                <label for="billet_vvip_prix">Prix (FCFA)</label>
                                <input type="number"
                                       id="billet_vvip_prix"
                                       name="billet_vvip_prix"
                                       value="{{ old('billet_vvip_prix', $data['billet_vvip_prix'] ?? '') }}"
                                       min="0"
                                       step="100"
                                       placeholder="Prix en FCFA">
                                <small class="form-hint">Laissez 0 ou vide pour un billet gratuit</small>
                                <div class="gratuit-badge" x-show="parseFloat(vvipPrix) === 0 || vvipPrix === ''" x-transition>
                                    Gratuit
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="est_gratuit" x-model="estGratuit">
            </div>

            {{-- Navigation --}}
            <div class="form-navigation">
                <a href="{{ route('events.create.step2') }}" class="btn btn-secondary btn-prev">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Precedent
                </a>
                <button type="submit" class="btn btn-primary btn-next">
                    Suivant
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.create-event-page {
    min-height: calc(100vh - 200px);
    padding: 120px 24px 60px;
    background: #F9F9F9;
}

.create-event-container {
    max-width: 900px;
    margin: 0 auto;
}

.create-event-header {
    text-align: center;
    margin-bottom: 40px;
}

.create-event-header h1 {
    font-family: 'Eras Medium ITC', serif;
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.create-event-header p {
    font-size: 14px;
    color: #666;
}

.form-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    padding: 32px;
    margin-bottom: 24px;
}

.card-title {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.card-subtitle {
    font-size: 14px;
    color: #666;
    margin-bottom: 24px;
}

.error-alert {
    background: #FEE2E2;
    color: #CC0000;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

.ticket-types-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.ticket-type-card {
    border: 2px solid #E0E0E0;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.25s ease;
}

.ticket-type-card.active {
    border-color: var(--accent-color);
    background: rgba(0, 0, 0, 0.02);
}

.ticket-type-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.ticket-type-badge {
    padding: 6px 14px;
    border-radius: 20px;
    color: white;
    font-weight: 600;
    font-size: 13px;
}

.toggle-switch {
    position: relative;
    width: 48px;
    height: 26px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: 0.3s;
    border-radius: 26px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

.toggle-switch input:checked + .toggle-slider {
    background-color: var(--accent-color);
}

.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(22px);
}

.ticket-type-body {
    padding-top: 16px;
    border-top: 1px solid #E0E0E0;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 500;
    color: #444444;
    margin-bottom: 6px;
}

.form-group input {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #E0E0E0;
    border-radius: 8px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    color: #1a1a1a;
    background: white;
    transition: all 0.25s ease;
    outline: none;
}

.form-group input:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.08);
}

.form-hint {
    display: block;
    font-size: 11px;
    color: #888;
    margin-top: 4px;
}

.gratuit-badge {
    display: inline-block;
    background: #22C55E;
    color: white;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    margin-top: 8px;
}

.form-navigation {
    display: flex;
    justify-content: space-between;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 28px;
    border-radius: 40px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
    border: none;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

.btn-primary {
    background: linear-gradient(135deg, #CC0000, #910000);
    color: white;
    min-width: 160px;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(204, 0, 0, 0.35);
}

.btn-secondary {
    background: white;
    color: #555;
    border: 1.5px solid #E0E0E0;
}

.btn-secondary:hover {
    background: #F5F5F5;
}

@media (max-width: 768px) {
    .ticket-types-grid {
        grid-template-columns: 1fr;
    }

    .create-event-page {
        padding: 100px 16px 40px;
    }

    .form-card {
        padding: 20px 16px;
    }

    .form-navigation {
        flex-direction: column-reverse;
        gap: 12px;
    }

    .btn {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
function ticketManager() {
    return {
        classiqueActive: {{ old('billet_classique_actif', $data['billet_classique_actif'] ?? 'false') === '1' || old('billet_classique_actif', $data['billet_classique_actif'] ?? false) === true ? 'true' : 'false' }},
        classiquePrix: '{{ old('billet_classique_prix', $data['billet_classique_prix'] ?? '') }}',
        vipActive: {{ old('billet_vip_actif', $data['billet_vip_actif'] ?? 'false') === '1' || old('billet_vip_actif', $data['billet_vip_actif'] ?? false) === true ? 'true' : 'false' }},
        vipPrix: '{{ old('billet_vip_prix', $data['billet_vip_prix'] ?? '') }}',
        vvipActive: {{ old('billet_vvip_actif', $data['billet_vvip_actif'] ?? 'false') === '1' || old('billet_vvip_actif', $data['billet_vvip_actif'] ?? false) === true ? 'true' : 'false' }},
        vvipPrix: '{{ old('billet_vvip_prix', $data['billet_vvip_prix'] ?? '') }}',

        get estGratuit() {
            const classiqueIsFree = this.classiqueActive && (parseFloat(this.classiquePrix) === 0 || this.classiquePrix === '');
            const vipIsFree = this.vipActive && (parseFloat(this.vipPrix) === 0 || this.vipPrix === '');
            const vvipIsFree = this.vvipActive && (parseFloat(this.vvipPrix) === 0 || this.vvipPrix === '');

            const hasActive = this.classiqueActive || this.vipActive || this.vvipActive;
            const allActiveAreFree = (!this.classiqueActive || classiqueIsFree) &&
                                       (!this.vipActive || vipIsFree) &&
                                       (!this.vvipActive || vvipIsFree);

            return hasActive && allActiveAreFree ? '1' : '0';
        }
    };
}
</script>
@endpush
@endsection