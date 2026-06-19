@extends('layouts.app')

@section('title', 'Evenements - ELEDJI')

@section('content')

{{-- Hero Section --}}
<section class="events-hero">
    <div class="events-hero-content">
        <h1 class="events-hero-title">
            DECOUVREZ DES EXPERIENCES INOUBLIABLES ET DES
            <span>EVENEMENTS SPECTACULAIRES</span>
        </h1>
        <div class="events-search">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="search-input" placeholder="Rechercher un evenement...">
        </div>
    </div>
</section>

{{-- New Events Section --}}
<section class="events-section">
    <div class="events-container">
        <div class="events-header">
            <h2>Nouvelle Evenements a <span>Lome</span></h2>
        </div>

        {{-- Filter Tabs --}}
        <div class="filter-tabs" id="filter-tabs">
            <button class="filter-tab active" data-filter="all" onclick="filterEvents('all', this)">TOUT</button>
            <button class="filter-tab" data-filter="gratuit" onclick="filterEvents('gratuit', this)">GRATUIT</button>
            <button class="filter-tab" data-filter="payant" onclick="filterEvents('payant', this)">PAYANT</button>
            @foreach($categories as $category)
                <button class="filter-tab" data-filter="{{ $category->slug }}" onclick="filterEvents('{{ $category->slug }}', this)">
                    {{ strtoupper($category->nom) }}
                </button>
            @endforeach
        </div>

        {{-- Events Grid --}}
        <div class="events-grid" id="events-grid">
            @if($events && $events->count() > 0)
                @foreach($events as $index => $event)
                    <a href="{{ route('events.show', $event) }}" class="event-card event-item" data-category="{{ $event->category->slug ?? 'all' }}" data-gratuit="{{ $event->est_gratuit ? '1' : '0' }}">
                        <div class="event-card-image">
                            <img src="{{ $event->image_url }}" alt="{{ $event->titre }}">
                            <div class="event-card-overlay"></div>
                            @if($event->premium_mise_en_avant)
                                <span class="event-badge">En vedette</span>
                            @endif
                        </div>
                        <div class="event-card-content">
                            <h3 class="event-card-title">{{ $event->titre }}</h3>
                            <div class="event-card-meta">
                                <div class="event-meta-item">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($event->date_heure)->translatedFormat('l j F') }}</span>
                                </div>
                                <div class="event-meta-item">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>{{ $event->lieu ?? 'Lome' }}</span>
                                </div>
                                <div class="event-meta-item event-price">
                                    <span>{{ $event->est_gratuit ? 'Gratuit' : 'Payant' }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                <div class="events-empty">
                    Aucun evenement trouve
                </div>
            @endif
        </div>
    </div>
</section>

@push('styles')
<style>
:root {
    --rouge: #CC0000;
    --rouge-dark: #910000;
    --gris-clair: #F9F9F9;
    --poppins: 'Poppins', sans-serif;
}

.events-hero {
    background: linear-gradient(135deg, #F7D6D3 0%, #E8C4C2 100%);
    padding: 140px 24px 60px;
    text-align: center;
}

.events-hero-content {
    max-width: 700px;
    margin: 0 auto;
}

.events-hero-title {
    font-size: clamp(20px, 4vw, 32px);
    font-weight: 800;
    color: #1a1a1a;
    line-height: 1.3;
    margin-bottom: 24px;
}

.events-hero-title span {
    color: var(--rouge);
    display: block;
    text-transform: uppercase;
}

.events-search {
    display: flex;
    align-items: center;
    background: white;
    border-radius: 40px;
    padding: 12px 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    gap: 12px;
    max-width: 480px;
    margin: 0 auto;
}

.events-search svg {
    color: var(--rouge);
    flex-shrink: 0;
}

.events-search input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 14px;
    font-family: var(--poppins);
    color: #333;
    background: transparent;
}

.events-search input::placeholder {
    color: #999;
}

.events-section {
    padding: 60px 24px;
    background: white;
}

.events-container {
    max-width: 1100px;
    margin: 0 auto;
}

.events-header {
    margin-bottom: 32px;
}

.events-header h2 {
    font-size: 26px;
    font-weight: 800;
    color: #1a1a1a;
}

.events-header h2 span {
    color: var(--rouge);
}

/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 32px;
    flex-wrap: wrap;
}

.filter-tab {
    padding: 10px 20px;
    border-radius: 40px;
    font-size: 13px;
    font-weight: 600;
    font-family: var(--poppins);
    cursor: pointer;
    transition: all 0.25s ease;
    border: 1.5px solid #e0e0e0;
    background: white;
    color: #555;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

.filter-tab:hover {
    background: #f5f5f5;
}

.filter-tab.active {
    background: var(--rouge);
    color: white;
    border-color: var(--rouge);
    box-shadow: 0 4px 16px rgba(204, 0, 0, 0.25);
}

/* Events Grid */
.events-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

@media (max-width: 900px) {
    .events-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 580px) {
    .events-grid {
        grid-template-columns: 1fr;
    }
}

/* Event Card */
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

.event-card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.event-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.event-card:hover .event-card-image img {
    transform: scale(1.08);
}

.event-card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.75) 0%, transparent 60%);
}

.event-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 40px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.event-card-content {
    padding: 16px 18px 18px;
}

.event-card-title {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 12px 0;
    line-height: 1.3;
}

.event-card-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.event-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #666;
}

.event-meta-item svg {
    color: var(--rouge);
    flex-shrink: 0;
}

.event-price {
    margin-top: 4px;
}

.event-price span {
    background: #F0F0F0;
    padding: 4px 10px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 11px;
    color: var(--rouge);
}

/* Empty state */
.events-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: #999;
    font-size: 14px;
}

/* Loading animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.event-card {
    animation: fadeInUp 0.5s ease-out both;
}

.event-card:nth-child(1) { animation-delay: 0.1s; }
.event-card:nth-child(2) { animation-delay: 0.2s; }
.event-card:nth-child(3) { animation-delay: 0.3s; }
.event-card:nth-child(4) { animation-delay: 0.4s; }
.event-card:nth-child(5) { animation-delay: 0.5s; }
.event-card:nth-child(6) { animation-delay: 0.6s; }

/* Responsive */
@media (max-width: 768px) {
    .events-hero {
        padding: 120px 24px 40px;
    }

    .events-hero-title {
        font-size: 22px;
    }

    .filter-tabs {
        gap: 8px;
    }

    .filter-tab {
        padding: 8px 14px;
        font-size: 12px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function filterEvents(category, button) {
    // Update active tab
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    button.classList.add('active');

    // Filter items
    const items = document.querySelectorAll('.event-item');
    items.forEach(item => {
        const itemCategory = item.getAttribute('data-category');
        const itemGratuit = item.getAttribute('data-gratuit');

        let show = false;

        if (category === 'all') {
            show = true;
        } else if (category === 'gratuit') {
            show = itemGratuit === '1';
        } else if (category === 'payant') {
            show = itemGratuit === '0';
        } else {
            show = itemCategory === category;
        }

        if (show) {
            item.style.display = 'block';
            item.style.animation = 'fadeInUp 0.4s ease-out';
        } else {
            item.style.display = 'none';
        }
    });
}

// Search Events
document.getElementById('search-input').addEventListener('keyup', function(e) {
    const searchTerm = this.value.toLowerCase();
    if (e.key === 'Enter' && searchTerm.length > 0) {
        window.location.href = `{{ route('events.index') }}?search=${encodeURIComponent(searchTerm)}`;
    }
});

// Auto-filter on page load based on URL params
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const gratuitParam = urlParams.get('gratuit');

    if (gratuitParam === '1') {
        const btn = document.querySelector('[data-filter="gratuit"]');
        if (btn) filterEvents('gratuit', btn);
    } else if (gratuitParam === '0') {
        const btn = document.querySelector('[data-filter="payant"]');
        if (btn) filterEvents('payant', btn);
    }
});
</script>
@endpush

@endsection