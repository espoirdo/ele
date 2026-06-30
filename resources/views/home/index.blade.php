@extends('layouts.app')

@section('title', 'Accueil - ELEDJI')

@section('content')

{{-- =========================================== --}}
{{-- HERO SECTION --}}
{{-- =========================================== --}}
<section class="eledji-hero" id="hero">
    <canvas id="particles-canvas"></canvas>
    <div class="hero-content">
        <div class="hero-badge" id="hero-badge">
            <span class="hero-badge-dot"></span>
            <span>Lome, Togo - Evenements live</span>
        </div>
        <h1 id="hero-title">{{ setting('home_hero_title_line1', 'DECOUVREZ DES EXPERIENCES') }}<br>{{ setting('home_hero_title_line2', 'INOUBLIABLES ET DES') }}</h1>
        <h2 id="hero-subtitle">{{ setting('home_hero_subtitle', 'EVENEMENTS SPECTACULAIRES') }}</h2>
        <form action="{{ route('events.index') }}" method="GET" class="hero-search" id="hero-search">
            <div class="hero-search-box">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" placeholder="{{ setting('home_search_placeholder', 'Rechercher un evenement, une categorie...') }}">
                <button type="submit">Rechercher</button>
            </div>
        </form>
        <div class="hero-stats" id="hero-stats">
            <div class="hero-stat">
                <span class="hero-stat-num">{{ $totalEvents }}+</span>
                <span class="hero-stat-label">{{ setting('home_stats_events_label', 'Evenements') }}</span>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
                <span class="hero-stat-num">{{ $totalCategories }}+</span>
                <span class="hero-stat-label">{{ setting('home_stats_categories_label', 'Categories') }}</span>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
                <span class="hero-stat-num">{{ $totalUsers }}+</span>
                <span class="hero-stat-label">{{ setting('home_stats_participants_label', 'Participants') }}</span>
            </div>
        </div>
    </div>
    <div class="hero-scroll-arrow">
        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</section>

{{-- =========================================== --}}
{{-- SECTION 2 -- TOP EVENEMENTS --}}
{{-- =========================================== --}}
<section class="eledji-section" id="section-vedette">
    <div class="section-header eledji-container">
        <div>
            <p class="section-label">{{ setting('home_featured_label', 'A ne pas manquer') }}</p>
            <h2 class="section-title">{{ setting('home_featured_title', 'Top des evenements') }}</h2>
        </div>
    </div>

    {{-- Desktop: Swiper carousel --}}
    <div class="swiper swiper-vedette desktop-carousel">
        <div class="swiper-wrapper">
            @forelse($top3 as $event)
                <div class="swiper-slide vedette-slide">
                    <a href="{{ route('events.show', $event) }}" class="vedette-card">
                        <img src="{{ $event->image_url }}" alt="{{ $event->titre }}" loading="lazy">
                        <div class="vedette-overlay"></div>
                        @if($event->premium_mise_en_avant)
                            <span class="vedette-badge">Premium</span>
                        @endif
                        <div class="vedette-info">
                            @if($event->category)
                                <span class="vedette-cat">{{ $event->category->nom }}</span>
                            @endif
                            <h3>{{ $event->titre }}</h3>
                            <p class="vedette-date">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $event->date->translatedFormat('l, d M Y') }} / {{ $event->heure }}
                            </p>
                            <p class="vedette-lieu">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $event->lieu }}
                            </p>
                        </div>
                        <div class="vedette-hover-cta">Voir les details</div>
                    </a>
                </div>
            @empty
                <div class="swiper-slide vedette-slide">
                    <div class="vedette-empty">Aucun evenement en vedette</div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Mobile: Custom carousel (below 768px) --}}
    <div class="mobile-carousel-wrapper" id="mobile-vedette-carousel">
        <div class="mobile-carousel-track" id="mobile-carousel-track">
            @forelse($top3 as $event)
                <div class="mobile-carousel-slide">
                    <a href="{{ route('events.show', $event) }}" class="vedette-card">
                        <img src="{{ $event->image_url }}" alt="{{ $event->titre }}" loading="lazy">
                        <div class="vedette-overlay"></div>
                        @if($event->premium_mise_en_avant)
                            <span class="vedette-badge">Premium</span>
                        @endif
                        <div class="vedette-info">
                            @if($event->category)
                                <span class="vedette-cat">{{ $event->category->nom }}</span>
                            @endif
                            <h3>{{ $event->titre }}</h3>
                            <p class="vedette-date">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $event->date->translatedFormat('l, d M Y') }} / {{ $event->heure }}
                            </p>
                            <p class="vedette-lieu">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $event->lieu }}
                            </p>
                        </div>
                        <div class="vedette-hover-cta">Voir les details</div>
                    </a>
                </div>
            @empty
                <div class="mobile-carousel-slide">
                    <div class="vedette-empty">Aucun evenement en vedette</div>
                </div>
            @endforelse
        </div>
        <div class="mobile-carousel-dots" id="mobile-carousel-dots"></div>
    </div>
</section>

{{-- =========================================== --}}
{{-- SECTION 3 -- EVENEMENTS RECENTS --}}
{{-- =========================================== --}}
<section class="eledji-section eledji-section-gray" id="section-recents" x-data="{ categorie: 'tout' }">
    <div class="eledji-container">
        <div class="section-header-flex">
            <div>
                <p class="section-label">Nouveautes</p>
                <h2 class="section-title">Nouvelle Evenements a <span class="text-red">Lome</span></h2>
            </div>
            <a href="{{ route('events.index') }}" class="btn-voir-plus">
                {{ setting('home_voir_plus_label', 'Voir plus') }}
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- Filtres --}}
        <div class="filtres-wrap">
            <button @click="categorie='tout'"
                    :class="categorie==='tout' ? 'filtre-actif' : 'filtre-inactif'"
                    class="filtre-btn">
                TOUT
            </button>
            @foreach($categories as $cat)
                <button @click="categorie='{{ $cat->slug }}'"
                        :class="categorie==='{{ $cat->slug }}' ? 'filtre-actif' : 'filtre-inactif'"
                        class="filtre-btn">
                    {{ $cat->nom }}
                </button>
            @endforeach
        </div>

        {{-- Grille 3 colonnes --}}
        <div class="events-grid">
            @foreach($events as $event)
                <div x-show="categorie==='tout' || categorie==='{{ $event->category?->slug }}'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-end="opacity-0 scale-95">
                    <a href="{{ route('events.show', $event) }}" class="event-card">
                        <div class="event-card-img">
                            <img src="{{ $event->image_url }}" alt="{{ $event->titre }}" loading="lazy">
                            <div class="event-card-overlay"></div>
                            @if($event->premium_mise_en_avant)
                                <span class="event-badge-premium">En vedette</span>
                            @endif
                            <div class="event-card-info">
                                <h3>{{ $event->titre }}</h3>
                                <p class="event-date">{{ $event->date->translatedFormat('l, d M') }} / {{ $event->heure }}</p>
                                <p class="event-lieu">{{ $event->lieu }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- =========================================== --}}
{{-- SECTION 4 -- SLIDER PROMO --}}
{{-- =========================================== --}}
<section class="eledji-section eledji-section-pink" id="section-promo">
    <div class="eledji-container">
        <div class="swiper swiper-promo">
            <div class="swiper-wrapper">
                @foreach([
                    ['titre'=>'Partagez, Apprenez, Evoluez','texte'=>'Plongez au coeur de l\'action avec nos conferences exclusives. Un moment privilegie ou experts et passionnes se rencontrent pour echanger des idees novatrices et batir l\'avenir de leur secteur.'],
                    ['titre'=>'Vivez des Moments Uniques','texte'=>'Des festivals epoustouflants, des masterclasses inspirantes, des soirees corporate inoubliables. ELEDJI vous connecte aux meilleurs evenements de Lome et du Togo.'],
                    ['titre'=>'Creez votre Evenement','texte'=>'Vous organisez un evenement ? Publiez-le sur ELEDJI et touchez des milliers de participants potentiels a Lome et dans toute la region.'],
                ] as $slide)
                    <div class="swiper-slide">
                        <div class="promo-slide">
                            <div class="promo-slide-img">
                                <img src="{{ $top3->first()?->image_url ?? 'https://picsum.photos/seed/promo/600/400' }}" loading="lazy">
                            </div>
                            <div class="promo-slide-text">
                                <h3>{{ $slide['titre'] }}</h3>
                                <p>{{ $slide['texte'] }}</p>
                                <a href="{{ route('events.index') }}" class="promo-voir">VOIR+</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination swiper-promo-pagination"></div>
        </div>
    </div>
</section>

{{-- =========================================== --}}
{{-- SECTION 5 -- CATEGORIES (Payant/Gratuit) --}}
{{-- =========================================== --}}
<section class="eledji-section" id="section-categories">
    <div class="eledji-container">
        <div class="text-center mb-10">
            <p class="section-label">Explorer</p>
            <h2 class="section-title">Trouvez votre style</h2>
        </div>
        <div class="cat-grid">
            <a href="{{ route('events.index', ['gratuit' => '1']) }}" class="cat-card">
                <img src="https://picsum.photos/seed/gratuit/500/350" alt="Evenements Gratuits" loading="lazy">
                <div class="cat-overlay"></div>
                <div class="cat-label cat-label-gratuit">EVENEMENTSgratuITS</div>
                <div class="cat-hover">
                    <span class="cat-hover-icon">
                        <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
            </a>
            <a href="{{ route('events.index', ['gratuit' => '0']) }}" class="cat-card">
                <img src="https://picsum.photos/seed/payant/500/350" alt="Evenements Payants" loading="lazy">
                <div class="cat-overlay"></div>
                <div class="cat-label cat-label-payant">EVENEMENTS PAYANTS</div>
                <div class="cat-hover">
                    <span class="cat-hover-icon">
                        <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </span>
                </div>
            </a>
        </div>
    </div>
</section>

@push('styles')
<style>
/* ===== VARIABLES ===== */
:root {
    --rouge: #CC0000;
    --rouge-dark: #910000;
    --rose-hero: #F7D6D3;
    --rose-pale: #F0D0D0;
    --gris-section: #F9F9F9;
    --texte: #1a1a1a;
    --poppins: 'Poppins', sans-serif;
}

/* ===== RESET & BASE ===== */
*, *::before, *::after { box-sizing: border-box; }
body { margin: 0; padding: 0; font-family: var(--poppins); overflow-x: hidden; }

/* ===== CONTAINER ===== */
.eledji-container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 24px;
}

/* ===== SECTIONS ===== */
.eledji-section { padding: 80px 0; }
.eledji-section-gray { background: var(--gris-section); }
.eledji-section-pink { background: var(--rose-pale); }

/* ===== LABELS SECTIONS ===== */
.section-label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: var(--rouge);
    margin: 0 0 6px 0;
}
.section-title {
    font-size: 26px;
    font-weight: 800;
    color: var(--texte);
    margin: 0;
}
.section-header { margin-bottom: 40px; }
.section-header-flex {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 16px;
}
.text-red { color: var(--rouge); }

/* ===== HERO ===== */
.eledji-hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    overflow: hidden;
    background: linear-gradient(135deg, #F7D6D3 0%, #E8C4C2 50%, #F7D6D3 100%);
    padding-top: 90px;
    max-width: 100%;
}

/* Mobile hero */
@media (max-width: 767px) {
    .eledji-hero {
        padding: 100px 20px 48px;
    }
}
#particles-canvas {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    opacity: 0.35;
}
.hero-content {
    position: relative;
    z-index: 2;
    padding: 0 24px;
    max-width: 860px;
    margin: 0 auto;
    max-width: 100%;
    overflow-x: hidden;
}
.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.22);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.35);
    border-radius: 100px;
    padding: 8px 18px;
    margin-bottom: 28px;
    color: #333;
    font-size: 13px;
    font-weight: 500;
}
.hero-badge-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #CC0000;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%,100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.3); }
}
.hero-content h1 {
    font-size: clamp(18px, 5vw, 36px);
    font-weight: 800;
    color: #1a1a1a;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    line-height: 1.15;
    margin: 0 0 8px 0;
    word-break: break-word;
    overflow-wrap: break-word;
    max-width: 100%;
}
.hero-content h2 {
    font-size: clamp(18px, 5vw, 36px);
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--rouge);
    margin: 0 0 36px 0;
    word-break: break-word;
    overflow-wrap: break-word;
    max-width: 100%;
}
.hero-search { width: 100%; max-width: 580px; margin: 0 auto; }

/* Mobile search */
@media (max-width: 767px) {
    .hero-search {
        width: 100%;
        max-width: 100%;
    }
}
.hero-search-box {
    display: flex;
    align-items: center;
    background: white;
    border-radius: 40px;
    padding: 8px 8px 8px 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    gap: 12px;
}
.hero-search-box svg { color: #999; flex-shrink: 0; }
.hero-search-box input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 14px;
    font-family: var(--poppins);
    color: #333;
    background: transparent;
    min-width: 0;
}
.hero-search-box input::placeholder { color: #aaa; }
.hero-search-box button {
    background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
    color: white;
    border: none;
    border-radius: 40px;
    padding: 12px 24px;
    font-size: 13px;
    font-weight: 600;
    font-family: var(--poppins);
    cursor: pointer;
    transition: all 0.25s ease;
    white-space: nowrap;
}
.hero-search-box button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(204, 0, 0, 0.3);
}
.hero-search-box button:active { transform: scale(0.98); }
.hero-stats {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 64px;
    margin-top: 44px;
    padding: 48px 24px;
}

/* Mobile stats - empilement vertical */
@media (max-width: 767px) {
    .hero-stats {
        flex-direction: column;
        gap: 0;
        padding: 24px 20px;
    }
    .hero-stat {
        padding: 20px 0;
        width: 100%;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    .hero-stat:last-child {
        border-bottom: none;
    }
    .hero-stat-divider {
        display: none;
    }
    .hero-stat-num {
        font-size: clamp(36px, 10vw, 56px);
    }
}
.hero-stat { text-align: center; }
.hero-stat-num {
    display: block;
    font-family: 'Eras Medium ITC', 'Eras ITC', Arial, sans-serif;
    font-size: 56px;
    font-weight: 800;
    color: #000000;
    line-height: 1.1;
}
.hero-stat-label {
    display: block;
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    font-weight: 500;
    color: #333333;
    margin-top: 8px;
}
.hero-stat-divider {
    width: 1px;
    height: 70px;
    background: rgba(0, 0, 0, 0.15);
}

/* Responsive stats */
@media (max-width: 600px) {
    .hero-stats {
        gap: 24px;
        padding: 32px 16px;
    }
    .hero-stat-num {
        font-size: 36px;
    }
    .hero-stat-label {
        font-size: 13px;
    }
    .hero-stat-divider {
        height: 50px;
    }
}

.hero-scroll-arrow {
    position: absolute;
    bottom: 28px;
    left: 50%;
    transform: translateX(-50%);
    color: rgba(0, 0, 0, 0.4);
    animation: bounce 2s infinite;
    z-index: 2;
}
@keyframes bounce {
    0%,100% { transform: translateX(-50%) translateY(0); }
    50% { transform: translateX(-50%) translateY(8px); }
}

/* ===== CAROUSEL VEDETTE ===== */
.swiper-vedette {
    width: 100%;
    padding: 16px 0 32px 0;
    padding-left: max(24px, calc((100vw - 1100px) / 2 + 24px)) !important;
    overflow: visible;
}
.vedette-slide { width: 320px !important; }
.vedette-card {
    display: block;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    height: 400px;
    text-decoration: none;
    transition: all 0.25s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
}
.vedette-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}
.vedette-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}
.vedette-card:hover img { transform: scale(1.06); }
.vedette-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.25) 55%, transparent 100%);
}
.vedette-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    background: linear-gradient(135deg, #CC0000, #910000);
    color: white;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 40px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.vedette-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 20px;
    color: white;
}
.vedette-cat {
    display: block;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #F4A0A0;
    margin-bottom: 6px;
}
.vedette-info h3 {
    font-size: 17px;
    font-weight: 700;
    margin: 0 0 8px 0;
    line-height: 1.3;
}
.vedette-date, .vedette-lieu {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    margin: 3px 0;
}
.vedette-date { color: #F4A0A0; }
.vedette-lieu { color: rgba(255, 255, 255, 0.75); }
.vedette-hover-cta {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(204, 0, 0, 0.85);
    opacity: 0;
    transition: opacity 0.3s;
    color: white;
    font-size: 13px;
    font-weight: 600;
}
.vedette-card:hover .vedette-hover-cta { opacity: 1; }
.vedette-empty {
    height: 400px;
    background: #eee;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 14px;
}

/* ===== MOBILE CAROUSEL (below 768px) ===== */
.mobile-carousel-wrapper {
    display: none;
    position: relative;
    width: 100%;
    overflow: hidden;
    padding-bottom: 20px;
}

.mobile-carousel-track {
    display: flex;
    transition: transform 0.5s ease-in-out;
    width: 100%;
}

.mobile-carousel-slide {
    flex: 0 0 100%;
    padding: 0 16px;
    box-sizing: border-box;
}

.mobile-carousel-dots {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 16px;
}

.mobile-carousel-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #E0E0E0;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    padding: 0;
}

.mobile-carousel-dot.active {
    background: #CC0000;
    width: 24px;
    border-radius: 4px;
}

@media (max-width: 767px) {
    .desktop-carousel { display: none !important; }
    .mobile-carousel-wrapper { display: block; }
}

/* ===== FILTRES ===== */
.filtres-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 32px;
}
.filtre-btn {
    padding: 12px 20px;
    border-radius: 40px;
    font-size: 13px;
    font-weight: 600;
    font-family: var(--poppins);
    cursor: pointer;
    transition: all 0.25s ease;
    border: 1.5px solid #e0e0e0;
    background: white;
    color: #555;
    min-height: 44px;
}
.filtre-btn:hover { background: #f5f5f5; }
.filtre-actif {
    background: var(--rouge) !important;
    color: white !important;
    border-color: var(--rouge) !important;
    box-shadow: 0 4px 16px rgba(204, 0, 0, 0.25);
}
.filtre-inactif {}

/* ===== GRILLE EVENEMENTS ===== */
.events-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
@media (max-width: 900px) { .events-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 580px) { .events-grid { grid-template-columns: 1fr; } }

.event-card {
    display: block;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    text-decoration: none;
    transition: all 0.25s ease;
}
.event-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}
.event-card-img {
    position: relative;
    height: 210px;
    overflow: hidden;
}
.event-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}
.event-card:hover .event-card-img img { transform: scale(1.08); }
.event-card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.75) 0%, transparent 60%);
}
.event-badge-premium {
    position: absolute;
    top: 10px;
    left: 10px;
    background: linear-gradient(135deg, #CC0000, #910000);
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 40px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.event-card-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 16px;
}
.event-card-info h3 {
    color: white;
    font-weight: 700;
    font-size: 14px;
    margin: 0 0 4px 0;
    line-height: 1.3;
}
.event-date { color: #F4A0A0; font-size: 12px; margin: 0 0 2px 0; }
.event-lieu { color: rgba(255, 255, 255, 0.78); font-size: 12px; margin: 0; }

/* ===== BOUTON VOIR PLUS ===== */
.btn-voir-plus {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 22px;
    background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
    color: white;
    border-radius: 40px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.25s ease;
    white-space: nowrap;
}
.btn-voir-plus:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(204, 0, 0, 0.3);
}

/* ===== SLIDER PROMO ===== */
.swiper-promo { border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07); }
.promo-slide {
    display: flex;
    min-height: 300px;
    background: white;
}
.promo-slide-img {
    width: 42%;
    overflow: hidden;
    position: relative;
    flex-shrink: 0;
}
.promo-slide-img img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.promo-slide-text {
    flex: 1;
    padding: 48px 52px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.promo-slide-text h3 {
    font-size: 20px;
    font-weight: 700;
    color: var(--rouge);
    margin: 0 0 16px 0;
}
.promo-slide-text p {
    font-size: 14px;
    color: #555;
    line-height: 1.75;
    margin: 0 0 20px 0;
}
.promo-voir {
    align-self: flex-end;
    font-weight: 800;
    font-size: 13px;
    color: var(--rouge-dark);
    text-decoration: none;
    letter-spacing: 0.05em;
}
.promo-voir:hover { text-decoration: underline; }
.swiper-promo-pagination { text-align: center; padding: 12px 0 16px; }
@media (max-width: 680px) {
    .promo-slide { flex-direction: column; }
    .promo-slide-img { width: 100%; height: 180px; }
    .promo-slide-text { padding: 24px; }
}

/* ===== CATEGORIES ===== */
.cat-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    max-width: 800px;
    margin: 0 auto;
}
@media (max-width: 768px) { .cat-grid { grid-template-columns: 1fr; max-width: 100%; } }
.cat-card {
    display: block;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    height: 270px;
    text-decoration: none;
    transition: all 0.25s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
}
.cat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}
.cat-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s ease;
}
.cat-card:hover img { transform: scale(1.08); }
.cat-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.65) 30%, rgba(0, 0, 0, 0.08) 100%);
    transition: opacity 0.3s;
}
.cat-label {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    text-align: center;
    padding: 18px 0;
    background: var(--rouge);
    color: white;
    font-weight: 800;
    font-size: 15px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
}
.cat-hover {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(204, 0, 0, 0.75);
    opacity: 0;
    transition: opacity 0.3s;
    padding-bottom: 60px;
}
.cat-hover-icon {
    transform: scale(0);
    transition: transform 0.3s ease;
}
.cat-card:hover .cat-hover { opacity: 1; }
.cat-card:hover .cat-hover-icon { transform: scale(1); }

/* ===== CATEGORIES PAYANT/GRATUIT ===== */
.cat-label-gratuit { background: #27ae60; }
.cat-label-payant { background: var(--rouge); }

/* ===== TEXT CENTER ===== */
.text-center { text-align: center; }
.mb-10 { margin-bottom: 40px; }
</style>
@endpush

@push('scripts')
<script>
// Mobile Carousel for Vedette (below 768px)
(function() {
    const wrapper = document.getElementById('mobile-vedette-carousel');
    if (!wrapper) return;

    const track = document.getElementById('mobile-carousel-track');
    const dotsContainer = document.getElementById('mobile-carousel-dots');
    const slides = track.querySelectorAll('.mobile-carousel-slide');

    if (slides.length === 0) return;

    let currentIndex = 0;
    let autoPlayInterval = null;
    let isPaused = false;
    const autoPlayDelay = 4000;
    const pauseDuration = 3000;

    // Create dots
    slides.forEach((_, index) => {
        const dot = document.createElement('button');
        dot.className = 'mobile-carousel-dot' + (index === 0 ? ' active' : '');
        dot.setAttribute('aria-label', 'Aller à la carte ' + (index + 1));
        dot.addEventListener('click', () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });

    const dots = dotsContainer.querySelectorAll('.mobile-carousel-dot');

    function updateCarousel() {
        track.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }

    function goToSlide(index) {
        currentIndex = index;
        if (currentIndex >= slides.length) currentIndex = 0;
        if (currentIndex < 0) currentIndex = slides.length - 1;
        updateCarousel();
    }

    function nextSlide() {
        if (!isPaused) {
            currentIndex++;
            if (currentIndex >= slides.length) currentIndex = 0;
            updateCarousel();
        }
    }

    // Auto-play
    function startAutoPlay() {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(nextSlide, autoPlayDelay);
    }

    function pauseAutoPlay() {
        isPaused = true;
        if (autoPlayInterval) {
            clearInterval(autoPlayInterval);
            autoPlayInterval = null;
        }
        setTimeout(() => {
            isPaused = false;
            startAutoPlay();
        }, pauseDuration);
    }

    // Touch/Swipe support
    let touchStartX = 0;
    let touchEndX = 0;
    const minSwipeDistance = 50;

    track.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    track.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        const swipeDistance = touchEndX - touchStartX;

        if (Math.abs(swipeDistance) > minSwipeDistance) {
            if (swipeDistance > 0) {
                // Swipe right - go to previous
                currentIndex--;
                if (currentIndex < 0) currentIndex = slides.length - 1;
            } else {
                // Swipe left - go to next
                currentIndex++;
                if (currentIndex >= slides.length) currentIndex = 0;
            }
            updateCarousel();
            pauseAutoPlay();
        }
    }, { passive: true });

    // Check screen width and init
    function checkScreenWidth() {
        const isMobile = window.innerWidth < 768;
        if (isMobile) {
            startAutoPlay();
        } else {
            if (autoPlayInterval) {
                clearInterval(autoPlayInterval);
                autoPlayInterval = null;
            }
        }
    }

    // Init
    checkScreenWidth();
    window.addEventListener('resize', checkScreenWidth);
})();

// Swiper vedette (desktop only)
new Swiper('.swiper-vedette', {
    slidesPerView: 'auto',
    spaceBetween: 20,
    loop: true,
    speed: 4500,
    autoplay: { delay: 0, disableOnInteraction: false },
    freeMode: { enabled: true, momentum: false },
    grabCursor: true,
});

// Swiper promo
new Swiper('.swiper-promo', {
    loop: true,
    effect: 'fade',
    speed: 900,
    autoplay: { delay: 5000, disableOnInteraction: false },
    pagination: {
        el: '.swiper-promo-pagination',
        clickable: true,
        renderBullet: (i, cls) =>
            `<span class="${cls}" style="width:10px;height:10px;border-radius:50%;
             display:inline-block;margin:0 4px;background:#CC0000;
             opacity:0.35;transition:opacity .3s,transform .3s"></span>`,
    },
});

// GSAP animations
gsap.registerPlugin(ScrollTrigger);
gsap.from('#hero-badge',    { y: -30, opacity: 0, duration: 0.8, delay: 0.2, ease: 'power2.out' });
gsap.from('#hero-title',    { y: 40, opacity: 0, duration: 0.9, delay: 0.4, ease: 'power2.out' });
gsap.from('#hero-subtitle', { y: 40, opacity: 0, duration: 0.9, delay: 0.6, ease: 'power2.out' });
gsap.from('#hero-search',   { y: 40, opacity: 0, duration: 0.9, delay: 0.8, ease: 'power2.out' });
gsap.from('#hero-stats',    { y: 30, opacity: 0, duration: 0.9, delay: 1.0, ease: 'power2.out' });

['#section-vedette', '#section-recents', '#section-promo', '#section-categories'].forEach(id => {
    gsap.from(id, {
        scrollTrigger: { trigger: id, start: 'top 82%', toggleActions: 'play none none none' },
        y: 50, opacity: 0, duration: 0.85, ease: 'power2.out'
    });
});

// Particules
const canvas = document.getElementById('particles-canvas');
if (canvas) {
    const ctx = canvas.getContext('2d');
    const resize = () => { canvas.width = canvas.offsetWidth; canvas.height = canvas.offsetHeight; };
    resize();
    window.addEventListener('resize', resize);
    const pts = Array.from({length: 45}, () => ({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        r: Math.random() * 2.5 + 0.8,
        dx: (Math.random() - 0.5) * 0.4,
        dy: (Math.random() - 0.5) * 0.4,
        a: Math.random() * 0.45 + 0.15,
    }));
    (function tick() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        pts.forEach(p => {
            p.x += p.dx;
            p.y += p.dy;
            if (p.x < 0 || p.x > canvas.width) p.dx *= -1;
            if (p.y < 0 || p.y > canvas.height) p.dy *= -1;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255, 255, 255, ${p.a})`;
            ctx.fill();
        });
        requestAnimationFrame(tick);
    })();
}
</script>
@endpush

@endsection