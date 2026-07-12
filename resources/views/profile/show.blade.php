@extends('layouts.app')

@section('title', 'Mon profil - ELEDJI')

@push('styles')
<style>
    :root {
        --rouge: #CC0000;
        --rouge-dark: #910000;
        --rose: #F7D6D3;
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

    .profile-page {
        min-height: calc(100vh - 80px);
        padding: 40px 24px 60px;
        background: var(--gris-bg);
        font-family: var(--poppins);
    }

    .profile-container {
        max-width: 860px;
        margin: 0 auto;
    }

    /* Section 1 - Carte d'identité */
    .identity-card {
        border-radius: var(--radius);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .identity-top {
        background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
        padding: 32px;
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .identity-bottom {
        background: white;
        padding: 24px 32px;
    }

    .avatar {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,0.4);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 700;
        color: white;
        background: rgba(255,255,255,0.2);
    }

    .identity-info {
        flex: 1;
    }

    .identity-name {
        font-family: 'Eras Medium ITC', serif;
        font-size: 24px;
        font-weight: 700;
        color: white;
        margin: 0 0 8px;
    }

    .identity-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .badge-vip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--or);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .badge-membre {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-connected {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #4ADE80;
        font-size: 13px;
        font-weight: 500;
    }

    .status-connected::before {
        content: '';
        width: 8px;
        height: 8px;
        background: #4ADE80;
        border-radius: 50%;
    }

    .btn-edit-profile {
        background: transparent;
        border: 1.5px solid white;
        color: white;
        padding: 10px 20px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-edit-profile:hover {
        background: white;
        color: var(--rouge);
    }

    /* Section Cards */
    .section-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 24px;
        margin-bottom: 24px;
    }

    .section-title {
        font-family: 'Eras Medium ITC', serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--texte);
        margin: 0 0 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title svg {
        width: 22px;
        height: 22px;
    }

    /* Info list */
    .info-list {
        display: grid;
        gap: 16px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--gris-border);
    }

    .info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-label {
        font-size: 13px;
        color: var(--texte-doux);
    }

    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--texte);
        text-align: right;
    }

    .verified-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
    }

    .verified-badge.verified {
        color: #22C55E;
    }

    .verified-badge.unverified {
        color: #F59E0B;
    }

    .btn-modify {
        background: transparent;
        border: 1.5px solid var(--rouge);
        color: var(--rouge);
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
    }

    .btn-modify:hover {
        background: var(--rouge);
        color: white;
    }

    /* Event list */
    .event-mini-list {
        display: grid;
        gap: 16px;
    }

    .event-mini-item {
        display: flex;
        gap: 16px;
        padding: 12px;
        background: var(--gris-bg);
        border-radius: var(--radius-sm);
        transition: all 0.25s ease;
    }

    .event-mini-item:hover {
        transform: translateX(4px);
    }

    .event-mini-img {
        width: 80px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .event-mini-img-placeholder {
        width: 80px;
        height: 60px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .event-mini-img-placeholder svg {
        width: 24px;
        height: 24px;
        color: white;
    }

    .event-mini-info {
        flex: 1;
        min-width: 0;
    }

    .event-mini-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--texte);
        margin: 0 0 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .event-mini-meta {
        font-size: 12px;
        color: var(--texte-doux);
        display: flex;
        gap: 12px;
    }

    .event-status {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 12px;
    }

    .event-status.publie {
        background: #DCFCE7;
        color: #16A34A;
    }

    .event-status.brouillon {
        background: #FEF3C7;
        color: #D97706;
    }

    .btn-view {
        font-size: 12px;
        color: var(--rouge);
        font-weight: 600;
        text-decoration: none;
    }

    .btn-view:hover {
        text-decoration: underline;
    }

    /* Booking list */
    .booking-mini-list {
        display: grid;
        gap: 16px;
    }

    .booking-mini-item {
        display: flex;
        gap: 16px;
        padding: 12px;
        background: var(--gris-bg);
        border-radius: var(--radius-sm);
    }

    .booking-mini-img {
        width: 80px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .booking-mini-img-placeholder {
        width: 80px;
        height: 60px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .booking-mini-img-placeholder svg {
        width: 24px;
        height: 24px;
        color: white;
    }

    .booking-mini-info {
        flex: 1;
        min-width: 0;
    }

    .booking-mini-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--texte);
        margin: 0 0 4px;
    }

    .booking-mini-meta {
        font-size: 12px;
        color: var(--texte-doux);
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 6px;
    }

    .booking-status {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 12px;
    }

    .booking-status.confirmee {
        background: #DCFCE7;
        color: #16A34A;
    }

    .booking-status.en_attente {
        background: #FEF3C7;
        color: #D97706;
    }

    .booking-status.annulee {
        background: #F1F5F9;
        color: #64748B;
    }

    .booking-number {
        font-size: 11px;
        color: var(--texte-doux);
    }

    .btn-ticket {
        font-size: 12px;
        color: var(--rouge);
        font-weight: 600;
        text-decoration: none;
    }

    .btn-ticket:hover {
        text-decoration: underline;
    }

    /* Empty states */
    .empty-state {
        text-align: center;
        padding: 32px 16px;
    }

    .empty-state-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 16px;
        background: var(--rose);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state-icon svg {
        width: 28px;
        height: 28px;
        color: var(--rouge);
    }

    .empty-state-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--texte);
        margin: 0 0 8px;
    }

    .empty-state-desc {
        font-size: 13px;
        color: var(--texte-doux);
        margin: 0 0 16px;
    }

    /* VIP Section */
    .vip-section {
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 24px;
    }

    .vip-section.not-vip {
        background: var(--or-bg);
        border: 2px solid var(--or);
    }

    .vip-section.is-vip {
        background: linear-gradient(135deg, #FFFBF0, #FFF5E6);
        border: 2px solid var(--or);
    }

    .vip-title {
        font-family: 'Eras Medium ITC', serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--texte);
        margin: 0 0 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .vip-title svg {
        width: 22px;
        height: 22px;
        color: var(--or);
    }

    .vip-desc {
        font-size: 14px;
        color: var(--texte-doux);
        margin: 0 0 16px;
        line-height: 1.6;
    }

    .vip-price {
        font-size: 28px;
        font-weight: 800;
        color: var(--rouge);
        margin-bottom: 16px;
    }

    .vip-price span {
        font-size: 14px;
        font-weight: 500;
        color: var(--texte-doux);
    }

    .btn-vip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 28px;
        border-radius: 30px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
        cursor: pointer;
        border: none;
    }

    .btn-vip.primary {
        background: var(--rouge);
        color: white;
    }

    .btn-vip.primary:hover {
        background: var(--rouge-dark);
        transform: translateY(-2px);
    }

    .btn-vip.gold {
        background: var(--or);
        color: white;
    }

    .btn-vip.gold:hover {
        background: #E09000;
        transform: translateY(-2px);
    }

    .vip-active-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .vip-expiry {
        font-size: 14px;
        color: var(--texte-doux);
    }

    .vip-expiry strong {
        color: var(--texte);
    }

    @media (max-width: 640px) {
        .identity-top {
            flex-direction: column;
            text-align: center;
            padding: 24px;
        }

        .identity-meta {
            justify-content: center;
        }

        .identity-bottom {
            padding: 20px 24px;
        }

        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }

        .info-value {
            text-align: left;
        }

        .event-mini-item, .booking-mini-item {
            flex-direction: column;
        }

        .event-mini-img, .event-mini-img-placeholder,
        .booking-mini-img, .booking-mini-img-placeholder {
            width: 100%;
            height: 120px;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-page">
    <div class="profile-container">

        {{-- Section 1: Carte d'identité --}}
        <div class="identity-card">
            <div class="identity-top">
                @if($user->avatar)
                    <img src="{{ $user->avatarUrl }}" alt="{{ $user->name }}" class="avatar">
                @else
                    <div class="avatar">
                        {{ implode('', array_map(fn($n) => $n[0], explode(' ', $user->name))) }}
                    </div>
                @endif
                <div class="identity-info">
                    <h1 class="identity-name">{{ $user->name }}</h1>
                    <div class="identity-meta">
                        @if($user->isVip())
                            <span class="badge-vip">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                VIP
                            </span>
                        @else
                            <span class="badge-membre">Membre</span>
                        @endif
                        <span class="status-connected">Connecté</span>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="btn-edit-profile">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Modifier
                </a>
            </div>
        </div>

        {{-- Section 5: VIP Status --}}
        <div class="vip-section {{ $user->isVip() ? 'is-vip' : 'not-vip' }}">
            @if(!$user->isVip())
                <h2 class="vip-title">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    Passez en VIP
                </h2>
                <p class="vip-desc">
                    Accédez à la Marketplace exclusive, obt的双子一个 badge VIP visible sur votre profil, et bien plus encore!
                </p>
                <p class="vip-price">{{ number_format($vipPrice, 0, ',', ' ') }} XOF <span>/ {{ $vipDuration }} jours</span></p>
                <a href="{{ route('vip.subscribe.show') }}" class="btn-vip primary">
                    Devenir VIP
                </a>
            @else
                <div class="vip-active-info">
                    <div>
                        <h2 class="vip-title">
                            <svg fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            VIP actif
                        </h2>
                        <p class="vip-expiry">Votre abonnement expire le <strong>{{ $user->vip_expires_at->translatedFormat('d M Y') }}</strong></p>
                    </div>
                    <a href="{{ route('marketplace.index') }}" class="btn-vip gold">
                        Accéder à la Marketplace
                    </a>
                </div>
            @endif
        </div>

        {{-- Section 2: Informations personnelles --}}
        <div class="section-card">
            <h2 class="section-title">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informations personnelles
            </h2>
            <div class="info-list">
                <div class="info-row">
                    <span class="info-label">Nom complet</span>
                    <span class="info-value">{{ $user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $user->email }}</span>
                </div>
                @if($user->phone)
                <div class="info-row">
                    <span class="info-label">Téléphone</span>
                    <span class="info-value">{{ $user->phone }}</span>
                </div>
                @endif
                @if($user->country)
                <div class="info-row">
                    <span class="info-label">Pays</span>
                    <span class="info-value">{{ $user->country }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Membre depuis</span>
                    <span class="info-value">{{ $user->created_at->translatedFormat('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email vérifié</span>
                    <span class="info-value">
                        @if($user->email_verified_at)
                            <span class="verified-badge verified">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Vérifié
                            </span>
                        @else
                            <span class="verified-badge unverified">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Non vérifié
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- Section 3: Mes événements créés --}}
        <div class="section-card">
            <h2 class="section-title">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Mes événements créés
            </h2>
            @if($myEvents->count() > 0)
                <div class="event-mini-list">
                    @foreach($myEvents as $event)
                        <div class="event-mini-item">
                            @if($event->image_couverture)
                                <img src="{{ Storage::url($event->image_couverture) }}" alt="{{ $event->titre }}" class="event-mini-img">
                            @else
                                <div class="event-mini-img-placeholder">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="event-mini-info">
                                <h3 class="event-mini-title">{{ $event->titre }}</h3>
                                <div class="event-mini-meta">
                                    <span>{{ $event->date->translatedFormat('d M Y') }}</span>
                                    <span class="event-status {{ $event->statut === 'publie' ? 'publie' : 'brouillon' }}">
                                        {{ $event->statut === 'publie' ? 'Publié' : 'Brouillon' }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('events.show', $event->slug) }}" class="btn-view">Voir</a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <p class="empty-state-title">Vous n'avez pas encore créé d'événement</p>
                    <p class="empty-state-desc">Partagez vos événements avec la communauté Eledji</p>
                    <a href="{{ route('events.create') }}" class="btn-vip primary">Créer mon premier événement</a>
                </div>
            @endif
        </div>

        {{-- Section 4: Mes participations --}}
        <div class="section-card">
            <h2 class="section-title">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                Mes participations
            </h2>
            @if($myBookings->count() > 0)
                <div class="booking-mini-list">
                    @foreach($myBookings as $booking)
                        <div class="booking-mini-item">
                            @if($booking->event && $booking->event->image_couverture)
                                <img src="{{ Storage::url($booking->event->image_couverture) }}" alt="{{ $booking->event->titre }}" class="booking-mini-img">
                            @else
                                <div class="booking-mini-img-placeholder">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="booking-mini-info">
                                @if($booking->event)
                                    <h3 class="booking-mini-title">{{ $booking->event->titre }}</h3>
                                    <div class="booking-mini-meta">
                                        <span>{{ $booking->event->date->translatedFormat('d M Y') }}</span>
                                        @if($booking->event->lieu)
                                            <span>{{ $booking->event->lieu }}</span>
                                        @endif
                                    </div>
                                @endif
                                <span class="booking-number">{{ $booking->numero_reservation }}</span>
                                <span class="booking-status {{ $booking->status }}">
                                    @if($booking->status === 'confirmee') Confirmé
                                    @elseif($booking->status === 'en_attente') En attente
                                    @else Annulé
                                    @endif
                                </span>
                                @if($booking->status === 'confirmee')
                                    <a href="{{ route('booking.success', $booking) }}" class="btn-ticket">Voir mon billet</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <p class="empty-state-title">Vous n'avez encore participé à aucun événement</p>
                    <p class="empty-state-desc">Découvrez les événements près de chez vous</p>
                    <a href="{{ route('events.index') }}" class="btn-vip primary">Découvrir les événements</a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection