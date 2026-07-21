@extends('layouts.app')

@section('title', 'Creer un evenement - Etape 3 sur 4 - ELEDJI')

@section('content')
@php
$step3 = session('event_step3', []);
@endphp

<div class="create-event-page" x-data="{
    typeEvenement: '{{ $step3['type_evenement'] ?? '' }}',
    classiqueActive: {{ isset($step3['billet_classique_actif']) && $step3['billet_classique_actif'] ? 'true' : 'false' }},
    classiquePrix: '{{ $step3['billet_classique_prix'] ?? '' }}',
    vipActive: {{ isset($step3['billet_vip_actif']) && $step3['billet_vip_actif'] ? 'true' : 'false' }},
    vipPrix: '{{ $step3['billet_vip_prix'] ?? '' }}',
    vvipActive: {{ isset($step3['billet_vvip_actif']) && $step3['billet_vvip_actif'] ? 'true' : 'false' }},
    vvipPrix: '{{ $step3['billet_vvip_prix'] ?? '' }}'
}">
    <div class="create-event-container">
        {{-- Progress Bar --}}
        @include('events.create.progress-bar', ['currentStep' => 3])

        {{-- Header --}}
        <div class="create-event-header">
            <h1>Creer un evenement - Etape 3 sur 4</h1>
            <p>Type d'entree</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('events.create.step3.post') }}" method="POST" class="create-event-form">
            @csrf

            {{-- Section 1: Gratuit ou Payant --}}
            <div class="form-card">
                <h3 class="card-title">Type d'entree</h3>
                <p class="card-subtitle">Votre evenement est-il gratuit ou payant ?</p>

                @error('type_evenement')
                    <p class="error-text">{{ $message }}</p>
                @enderror

                <div class="type-cards-grid">
                    {{-- Gratuit --}}
                    <div class="type-card"
                         :class="typeEvenement === 'gratuit' ? 'selected' : ''"
                         @click="typeEvenement = 'gratuit'">
                        <div class="type-card-icon">
                            <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h4 class="type-card-title">Gratuit</h4>
                        <p class="type-card-desc">Entree libre sans billet</p>
                    </div>

                    {{-- Payant --}}
                    <div class="type-card"
                         :class="typeEvenement === 'payant' ? 'selected' : ''"
                         @click="typeEvenement = 'payant'">
                        <div class="type-card-icon">
                            <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                        <h4 class="type-card-title">Payant</h4>
                        <p class="type-card-desc">Billet d'entree requis</p>
                    </div>
                </div>

                <input type="hidden" name="type_evenement" :value="typeEvenement">
            </div>

            {{-- Section 2: Types de billets (visible si Payant) --}}
            <div class="form-card" x-show="typeEvenement === 'payant'" x-transition>
                <h3 class="card-title">Types de billets</h3>
                <p class="card-subtitle">Activez au moins un type de billet pour votre evenement</p>

                @error('billets')
                    <p class="error-text">{{ $message }}</p>
                @enderror

                <div class="ticket-types-grid">
                    {{-- Classique --}}
                    <div class="ticket-type-card" :class="classiqueActive ? 'active' : ''" style="--accent-color: #333333;">
                        <div class="ticket-type-header">
                            <div class="ticket-type-badge" style="background: #333333;">Classique</div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="billet_classique_actif" value="1" x-model="classiqueActive">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="ticket-type-body" x-show="classiqueActive" x-transition>
                            <div class="form-group">
                                <label for="billet_classique_prix">Prix (FCFA)</label>
                                <input type="number"
                                       id="billet_classique_prix"
                                       name="billet_classique_prix"
                                       x-model="classiquePrix"
                                       value="{{ old('billet_classique_prix', $step3['billet_classique_prix'] ?? '') }}"
                                       min="0"
                                       step="100"
                                       placeholder="Prix en FCFA">
                                <div class="gratuit-badge" x-show="parseFloat(classiquePrix) === 0 || classiquePrix === ''" style="display:inline-block; background:#22C55E; color:white; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:600; margin-top:8px;">
                                    Gratuit
                                </div>
                                <div class="price-badge" x-show="parseFloat(classiquePrix) > 0" x-text="Number(classiquePrix).toLocaleString('fr-FR') + ' FCA'" style="display:inline-block; background:#333333; color:white; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:600; margin-top:8px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- VIP --}}
                    <div class="ticket-type-card" :class="vipActive ? 'active' : ''" style="--accent-color: #CC0000;">
                        <div class="ticket-type-header">
                            <div class="ticket-type-badge" style="background: #CC0000;">VIP</div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="billet_vip_actif" value="1" x-model="vipActive">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="ticket-type-body" x-show="vipActive" x-transition>
                            <div class="form-group">
                                <label for="billet_vip_prix">Prix (FCFA)</label>
                                <input type="number"
                                       id="billet_vip_prix"
                                       name="billet_vip_prix"
                                       x-model="vipPrix"
                                       value="{{ old('billet_vip_prix', $step3['billet_vip_prix'] ?? '') }}"
                                       min="0"
                                       step="100"
                                       placeholder="Prix en FCFA">
                                <div class="gratuit-badge" x-show="parseFloat(vipPrix) === 0 || vipPrix === ''" style="display:inline-block; background:#22C55E; color:white; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:600; margin-top:8px;">
                                    Gratuit
                                </div>
                                <div class="price-badge" x-show="parseFloat(vipPrix) > 0" x-text="Number(vipPrix).toLocaleString('fr-FR') + ' FCA'" style="display:inline-block; background:#CC0000; color:white; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:600; margin-top:8px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- VVIP --}}
                    <div class="ticket-type-card" :class="vvipActive ? 'active' : ''" style="--accent-color: #F5A623;">
                        <div class="ticket-type-header">
                            <div class="ticket-type-badge" style="background: #F5A623;">VVIP</div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="billet_vvip_actif" value="1" x-model="vvipActive">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="ticket-type-body" x-show="vvipActive" x-transition>
                            <div class="form-group">
                                <label for="billet_vvip_prix">Prix (FCFA)</label>
                                <input type="number"
                                       id="billet_vvip_prix"
                                       name="billet_vvip_prix"
                                       x-model="vvipPrix"
                                       value="{{ old('billet_vvip_prix', $step3['billet_vvip_prix'] ?? '') }}"
                                       min="0"
                                       step="100"
                                       placeholder="Prix en FCFA">
                                <div class="gratuit-badge" x-show="parseFloat(vvipPrix) === 0 || vvipPrix === ''" style="display:inline-block; background:#22C55E; color:white; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:600; margin-top:8px;">
                                    Gratuit
                                </div>
                                <div class="price-badge" x-show="parseFloat(vvipPrix) > 0" x-text="Number(vvipPrix).toLocaleString('fr-FR') + ' FCA'" style="display:inline-block; background:#F5A623; color:white; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:600; margin-top:8px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

.error-text {
    color: #CC0000;
    font-size: 13px;
    margin-top: 8px;
}

.type-cards-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.type-card {
    border: 1.5px solid #E0E0E0;
    border-radius: 12px;
    padding: 32px 24px;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s ease;
    background: white;
}

.type-card:hover {
    border-color: #CCCCCC;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.type-card.selected {
    border: 2px solid #CC0000;
    background: #FFF5F5;
}

.type-card-icon {
    margin-bottom: 16px;
    color: #666;
}

.type-card.selected .type-card-icon {
    color: #CC0000;
}

.type-card-title {
    font-size: 18px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.type-card-desc {
    font-size: 14px;
    color: #666;
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
    .type-cards-grid {
        grid-template-columns: 1fr;
    }

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
@endsection