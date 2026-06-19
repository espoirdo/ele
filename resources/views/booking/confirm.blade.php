@extends('layouts.app')

@section('title', 'Confirmer participation - ELEDJI')

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
    --radius-sm: 12px;
    --shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
}
*, *::before, *::after { box-sizing: border-box; }

.confirm-page {
    min-height: calc(100vh - 80px);
    padding: 48px 24px;
    background: var(--gris-bg);
    font-family: var(--poppins);
}

.confirm-container {
    max-width: 560px;
    margin: 0 auto;
}

.confirm-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 32px;
}

.event-summary {
    display: flex;
    gap: 16px;
    align-items: center;
    padding: 16px;
    background: var(--gris-bg);
    border-radius: var(--radius-sm);
    margin-bottom: 24px;
}

.event-img {
    width: 80px;
    height: 60px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
}

.event-info {
    flex: 1;
    min-width: 0;
}

.event-title {
    font-family: 'Eras Medium ITC', serif;
    font-size: 16px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 6px;
}

.event-meta {
    display: flex;
    flex-direction: column;
    gap: 4px;
    font-size: 12px;
    color: var(--texte-doux);
}

.event-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.event-meta-item svg {
    width: 14px;
    height: 14px;
}

.badge-gratuit {
    display: inline-flex;
    align-items: center;
    background: var(--vert-bg);
    color: var(--vert-doux);
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
    margin-top: 8px;
}

.confirm-title {
    font-family: 'Eras Medium ITC', serif;
    font-size: 20px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 24px;
    text-align: center;
}

.places-selector {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    margin-bottom: 16px;
}

.places-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--rouge);
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

.places-btn:hover {
    background: var(--rouge-dark);
    transform: scale(1.05);
}

.places-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
}

.places-count {
    width: 48px;
    font-size: 20px;
    font-weight: 700;
    color: var(--texte);
    text-align: center;
}

.places-info {
    text-align: center;
    font-size: 13px;
    color: var(--texte-doux);
    margin-bottom: 24px;
}

.places-info span {
    color: var(--rouge);
    font-weight: 600;
}

.confirm-actions {
    display: flex;
    gap: 12px;
}

.btn-annuler {
    flex: 1;
    padding: 14px;
    background: white;
    color: var(--texte-doux);
    border: 1.5px solid var(--gris-border);
    border-radius: 40px;
    font-size: 14px;
    font-weight: 600;
    font-family: var(--poppins);
    text-decoration: none;
    text-align: center;
    transition: all 0.25s ease;
    cursor: pointer;
}

.btn-annuler:hover {
    background: var(--gris-bg);
}

.btn-confirmer {
    flex: 1;
    padding: 14px;
    background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
    color: white;
    border: none;
    border-radius: 40px;
    font-size: 14px;
    font-weight: 600;
    font-family: var(--poppins);
    cursor: pointer;
    transition: all 0.25s ease;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

.btn-confirmer:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(204, 0, 0, 0.35);
}

@media (max-width: 480px) {
    .confirm-card {
        padding: 24px 20px;
    }

    .event-summary {
        flex-direction: column;
        text-align: center;
    }

    .event-img {
        width: 100%;
        height: 120px;
    }
}
</style>
@endpush

@section('content')
<div class="confirm-page">
    <div class="confirm-container">
        <div class="confirm-card" x-data="{ nbPlaces: 1 }">

            <div class="event-summary">
                @if($event->image_couverture)
                    <img src="{{ Storage::url($event->image_couverture) }}"
                         alt="{{ $event->titre }}"
                         class="event-img">
                @else
                    <div class="event-img" style="background: linear-gradient(135deg, var(--rouge), var(--rouge-dark)); display: flex; align-items: center; justify-content: center;">
                        <span style="color: white; font-weight: 700; font-size: 18px;">ELEDJI</span>
                    </div>
                @endif
                <div class="event-info">
                    <h2 class="event-title">{{ $event->titre }}</h2>
                    <div class="event-meta">
                        <div class="event-meta-item">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $event->date->translatedFormat('d M Y') }}
                        </div>
                        <div class="event-meta-item">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $event->lieu }}
                        </div>
                    </div>
                    <span class="badge-gratuit">Entree gratuite</span>
                </div>
            </div>

            <h1 class="confirm-title">Confirmer votre participation</h1>

            <form action="{{ route('booking.confirm.store', $event->slug) }}" method="POST">
                @csrf

                <div class="places-selector">
                    <button type="button"
                            class="places-btn"
                            @click="if(nbPlaces > 1) nbPlaces--">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                        </svg>
                    </button>

                    <input type="hidden" name="nb_places" x-model="nbPlaces">
                    <span class="places-count" x-text="nbPlaces">1</span>

                    <button type="button"
                            class="places-btn"
                            @click="if(nbPlaces < 5) nbPlaces++"
                            :disabled="nbPlaces >= 5">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>

                <p class="places-info">
                    Il reste <span>{{ $event->nb_places }}</span> places disponibles pour cet evenement
                </p>

                <div class="confirm-actions">
                    <a href="{{ route('events.show', $event->slug) }}" class="btn-annuler">
                        Annuler
                    </a>
                    <button type="submit" class="btn-confirmer">
                        Confirmer ma participation
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection