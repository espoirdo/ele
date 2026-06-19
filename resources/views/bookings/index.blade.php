@extends('layouts.app')

@section('title', 'Mes reservations - ELEDJI')

@push('styles')
<style>
:root {
    --rouge: #CC0000;
    --rouge-dark: #910000;
    --vert-doux: #2E7D32;
    --vert-bg: #E8F5E9;
    --orange-doux: #F57C00;
    --orange-bg: #FFF3E0;
    --gris-doux: #757575;
    --gris-bg: #F9F9F9;
    --gris-border: #E0E0E0;
    --texte: #1a1a1a;
    --texte-doux: #666;
    --poppins: 'Poppins', sans-serif;
    --radius: 12px;
    --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}
*, *::before, *::after { box-sizing: border-box; }

.bookings-page {
    min-height: calc(100vh - 80px);
    padding: 40px 24px;
    background: var(--gris-bg);
    font-family: var(--poppins);
}

.bookings-container {
    max-width: 900px;
    margin: 0 auto;
}

.page-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--texte);
    margin: 0 0 8px;
}

.page-subtitle {
    font-size: 14px;
    color: var(--texte-doux);
    margin: 0 0 32px;
}

.filters {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.25s ease;
    background: white;
    color: var(--texte-doux);
    border: 1.5px solid var(--gris-border);
}

.filter-btn:hover {
    border-color: var(--rouge);
    color: var(--rouge);
}

.filter-btn.active {
    background: var(--rouge);
    color: white;
    border-color: var(--rouge);
}

.bookings-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.booking-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    display: flex;
    transition: all 0.25s ease;
}

.booking-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.booking-image {
    width: 160px;
    height: 120px;
    flex-shrink: 0;
    position: relative;
}

.booking-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.booking-image-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
    display: flex;
    align-items: center;
    justify-content: center;
}

.booking-image-placeholder span {
    color: white;
    font-weight: 700;
    font-size: 18px;
}

.booking-content {
    flex: 1;
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
}

.booking-event-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 8px;
    text-decoration: none;
}

.booking-event-title:hover {
    color: var(--rouge);
}

.booking-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

.booking-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--texte-doux);
}

.booking-meta-item svg {
    width: 14px;
    height: 14px;
}

.booking-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 12px;
    border-top: 1px solid var(--gris-border);
}

.booking-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.booking-status.confirmee {
    background: var(--vert-bg);
    color: var(--vert-doux);
}

.booking-status.en_attente {
    background: var(--orange-bg);
    color: var(--orange-doux);
}

.booking-status.annulee {
    background: #F5F5F5;
    color: var(--gris-doux);
}

.booking-number {
    font-family: 'Poppins', monospace;
    font-size: 13px;
    font-weight: 600;
    color: var(--texte);
}

.booking-actions {
    display: flex;
    gap: 8px;
}

.btn-view-ticket {
    padding: 8px 16px;
    background: var(--rouge);
    color: white;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.25s ease;
}

.btn-view-ticket:hover {
    background: var(--rouge-dark);
}

.btn-pay {
    padding: 8px 16px;
    background: var(--orange-doux);
    color: white;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.25s ease;
}

.btn-pay:hover {
    background: #E65100;
}

.empty-state {
    text-align: center;
    padding: 60px 24px;
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 16px;
    background: var(--gris-bg);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon svg {
    width: 36px;
    height: 36px;
    stroke: #ccc;
}

.empty-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 8px;
}

.empty-text {
    font-size: 14px;
    color: var(--texte-doux);
    margin: 0 0 24px;
}

.btn-discover {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: var(--rouge);
    color: white;
    border-radius: 40px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.25s ease;
}

.btn-discover:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(204, 0, 0, 0.35);
}

@media (max-width: 600px) {
    .booking-card {
        flex-direction: column;
    }

    .booking-image {
        width: 100%;
        height: 140px;
    }
}
</style>
@endpush

@section('content')
<div class="bookings-page">
    <div class="bookings-container">
        <h1 class="page-title">Mes reservations</h1>
        <p class="page-subtitle">Suivez toutes vos reservations et billets d'evenements</p>

        <div class="filters">
            <a href="{{ route('user.bookings') }}"
               class="filter-btn {{ !request('status') ? 'active' : '' }}">
                Toutes
            </a>
            <a href="{{ route('user.bookings', ['status' => 'confirmee']) }}"
               class="filter-btn {{ request('status') == 'confirmee' ? 'active' : '' }}">
                Confirmees
            </a>
            <a href="{{ route('user.bookings', ['status' => 'en_attente']) }}"
               class="filter-btn {{ request('status') == 'en_attente' ? 'active' : '' }}">
                En attente
            </a>
            <a href="{{ route('user.bookings', ['status' => 'annulee']) }}"
               class="filter-btn {{ request('status') == 'annulee' ? 'active' : '' }}">
                Annulees
            </a>
        </div>

        @if($bookings->count() > 0)
            <div class="bookings-grid">
                @foreach($bookings as $booking)
                    <div class="booking-card">
                        <div class="booking-image">
                            @if($booking->event && $booking->event->image_couverture)
                                <img src="{{ Storage::url($booking->event->image_couverture) }}"
                                     alt="{{ $booking->event->titre }}">
                            @else
                                <div class="booking-image-placeholder">
                                    <span>ELEDJI</span>
                                </div>
                            @endif
                        </div>

                        <div class="booking-content">
                            <a href="{{ route('events.show', $booking->event->slug) }}"
                               class="booking-event-title">
                                {{ $booking->event->titre ?? 'Evenement' }}
                            </a>

                            <div class="booking-meta">
                                <div class="booking-meta-item">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $booking->event->date->translatedFormat('d M Y') ?? '-' }}
                                </div>
                                <div class="booking-meta-item">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ Str::limit($booking->event->lieu ?? '-', 20) }}
                                </div>
                                <div class="booking-meta-item">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $booking->nb_places }} place(s)
                                </div>
                            </div>

                            <div class="booking-footer">
                                <div>
                                    <span class="booking-status {{ $booking->status }}">
                                        @switch($booking->status)
                                            @case('confirmee')
                                                Confirmee
                                                @break
                                            @case('en_attente')
                                                En attente
                                                @break
                                            @case('annulee')
                                                Annulee
                                                @break
                                        @endswitch
                                    </span>
                                    @if($booking->numero_reservation)
                                        <div class="booking-number" style="margin-top: 6px;">
                                            {{ $booking->numero_reservation }}
                                        </div>
                                    @endif
                                </div>

                                <div class="booking-actions">
                                    @if($booking->status === 'confirmee')
                                        <a href="{{ route('booking.success', $booking) }}" class="btn-view-ticket">
                                            Voir le billet
                                        </a>
                                    @elseif($booking->status === 'en_attente')
                                        <a href="{{ route('payment.show', $booking->event->slug) }}" class="btn-pay">
                                            Payer
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 24px;">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <h3 class="empty-title">Aucune reservation</h3>
                <p class="empty-text">Vous n'avez pas encore reserve d'evenement. Decouvrez les evenements disponibles !</p>
                <a href="{{ route('events.index') }}" class="btn-discover">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Decouvrir les evenements
                </a>
            </div>
        @endif
    </div>
</div>
@endsection