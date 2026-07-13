@extends('layouts.app')

@section('title', 'Mon tableau de bord - ELEDJI')

@push('styles')
<style>
    :root {
        --rouge: #CC0000;
        --rouge-dark: #910000;
        --rose: #F7D6D3;
        --gris-bg: #F5F5F5;
        --gris-border: #E0E0E0;
        --texte: #1a1a1a;
        --texte-doux: #666666;
        --poppins: 'Poppins', sans-serif;
        --radius: 16px;
        --radius-sm: 12px;
        --shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
        --shadow-sm: 0 2px 12px rgba(0, 0, 0, 0.06);
        --or: #F5A623;
        --or-bg: #FFFBF0;
    }
    *, *::before, *::after { box-sizing: border-box; }

    .dashboard-page {
        min-height: calc(100vh - 80px);
        padding: 100px 24px 48px;
        background: var(--gris-bg);
        font-family: var(--poppins);
    }

    .dashboard-container {
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 24px;
        align-items: start;
    }

    /* ===== SIDEBAR ===== */
    .dashboard-sidebar {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 32px 24px;
        position: sticky;
        top: 100px;
    }

    .sidebar-avatar {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 700;
        color: white;
        background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
        overflow: hidden;
    }

    .sidebar-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .sidebar-name {
        font-family: 'Eras Medium ITC', serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--texte);
        text-align: center;
        margin-bottom: 8px;
    }

    .sidebar-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin: 0 auto 12px;
        display: block;
        text-align: center;
        width: fit-content;
    }

    .sidebar-badge.vip {
        background: var(--or-bg);
        color: var(--or);
    }

    .sidebar-badge.membre {
        background: #F1F5F9;
        color: var(--texte-doux);
    }

    .sidebar-email {
        font-size: 13px;
        color: var(--texte-doux);
        text-align: center;
        margin-bottom: 4px;
    }

    .sidebar-date {
        font-size: 12px;
        color: #999;
        text-align: center;
        margin-bottom: 20px;
    }

    .sidebar-divider {
        border-top: 1px solid #F0F0F0;
        margin: 20px 0;
    }

    .sidebar-nav {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 14px;
        color: #444444;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .sidebar-link:hover {
        background: #F9F9F9;
    }

    .sidebar-link.active {
        background: #FFF0F0;
        color: var(--rouge);
        font-weight: 600;
    }

    .sidebar-link svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .sidebar-link.logout {
        color: var(--rouge);
        margin-top: 8px;
        border-top: 1px solid #F0F0F0;
        padding-top: 16px;
    }

    /* ===== MAIN CONTENT ===== */
    .dashboard-content {
        min-width: 0;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: var(--radius-sm);
        box-shadow: var(--shadow-sm);
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon.red {
        background: rgba(204, 0, 0, 0.1);
    }

    .stat-icon.red svg {
        color: var(--rouge);
    }

    .stat-icon.gold {
        background: rgba(245, 166, 35, 0.1);
    }

    .stat-icon.gold svg {
        color: var(--or);
    }

    .stat-icon.gray {
        background: rgba(102, 102, 102, 0.1);
    }

    .stat-icon.gray svg {
        color: var(--texte-doux);
    }

    .stat-info {
        flex: 1;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--texte);
        line-height: 1.2;
    }

    .stat-label {
        font-size: 12px;
        color: var(--texte-doux);
    }

    /* Sections */
    .content-section {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 24px;
        margin-bottom: 24px;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .section-title {
        font-family: 'Eras Medium ITC', serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--texte);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title svg {
        width: 22px;
        height: 22px;
    }

    .section-link {
        font-size: 13px;
        color: var(--rouge);
        font-weight: 600;
        text-decoration: none;
    }

    .section-link:hover {
        text-decoration: underline;
    }

    /* Activity List */
    .activity-list {
        display: grid;
        gap: 12px;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px;
        background: var(--gris-bg);
        border-radius: var(--radius-sm);
    }

    .activity-img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .activity-img-placeholder {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .activity-img-placeholder svg {
        width: 20px;
        height: 20px;
        color: white;
    }

    .activity-info {
        flex: 1;
        min-width: 0;
    }

    .activity-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--texte);
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .activity-meta {
        font-size: 12px;
        color: var(--texte-doux);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .activity-status {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 12px;
    }

    .activity-status.publie {
        background: #DCFCE7;
        color: #16A34A;
    }

    .activity-status.brouillon {
        background: #FEF3C7;
        color: #D97706;
    }

    .activity-status.confirmee {
        background: #DCFCE7;
        color: #16A34A;
    }

    .activity-status.en_attente {
        background: #FEF3C7;
        color: #D97706;
    }

    /* VIP Banner */
    .vip-banner {
        background: linear-gradient(135deg, var(--or-bg), #FFF);
        border-left: 4px solid var(--or);
        border-radius: var(--radius-sm);
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }

    .vip-banner-text h3 {
        font-family: 'Eras Medium ITC', serif;
        font-size: 18px;
        color: var(--texte);
        margin: 0 0 4px;
    }

    .vip-banner-text p {
        font-size: 13px;
        color: var(--texte-doux);
        margin: 0;
    }

    .btn-vip {
        padding: 10px 20px;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
        border: none;
        cursor: pointer;
    }

    .btn-vip.primary {
        background: var(--or);
        color: white;
    }

    .btn-vip.primary:hover {
        background: #E09000;
    }

    /* Profile View */
    .profile-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .profile-field {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .profile-label {
        font-size: 12px;
        color: var(--texte-doux);
    }

    .profile-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--texte);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .verified-icon {
        width: 16px;
        height: 16px;
    }

    .verified-icon.verified {
        color: #22C55E;
    }

    .verified-icon.unverified {
        color: #F59E0B;
    }

    /* Events List */
    .events-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .event-row {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px;
        background: var(--gris-bg);
        border-radius: var(--radius-sm);
    }

    .event-thumb {
        width: 64px;
        height: 64px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .event-thumb-placeholder {
        width: 64px;
        height: 64px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .event-thumb-placeholder svg {
        width: 24px;
        height: 24px;
        color: white;
    }

    .event-details {
        flex: 1;
        min-width: 0;
    }

    .event-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--texte);
        margin-bottom: 4px;
    }

    .event-meta {
        font-size: 12px;
        color: var(--texte-doux);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .event-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 12px;
    }

    .event-badge.publie {
        background: #DCFCE7;
        color: #16A34A;
    }

    .event-badge.brouillon {
        background: #FEF3C7;
        color: #D97706;
    }

    .event-badge.complet {
        background: #FEE2E2;
        color: var(--rouge);
    }

    .event-actions {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-action.view {
        background: transparent;
        border: 1px solid var(--gris-border);
        color: var(--texte-doux);
    }

    .btn-action.view:hover {
        border-color: var(--rouge);
        color: var(--rouge);
    }

    .btn-action.edit {
        background: var(--rouge);
        color: white;
    }

    .btn-action.edit:hover {
        background: var(--rouge-dark);
    }

    .btn-create {
        padding: 12px 24px;
        background: var(--rouge);
        color: white;
        border: none;
        border-radius: 40px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .btn-create:hover {
        background: var(--rouge-dark);
    }

    /* Tickets List */
    .ticket-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: var(--gris-bg);
        border-radius: var(--radius-sm);
        position: relative;
    }

    .ticket-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
        background: var(--rouge);
        border-radius: var(--radius-sm) 0 0 var(--radius-sm);
    }

    .ticket-card.cancelled {
        opacity: 0.6;
    }

    .ticket-img {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .ticket-img-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .ticket-img-placeholder svg {
        width: 28px;
        height: 28px;
        color: white;
    }

    .ticket-info {
        flex: 1;
        min-width: 0;
    }

    .ticket-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--texte);
        margin-bottom: 4px;
    }

    .ticket-meta {
        font-size: 12px;
        color: var(--texte-doux);
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 6px;
    }

    .ticket-number {
        font-family: monospace;
        font-weight: 700;
        font-size: 12px;
    }

    .ticket-status {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 12px;
    }

    .ticket-status.confirmee {
        background: #DCFCE7;
        color: #16A34A;
    }

    .ticket-status.en_attente {
        background: #FEF3C7;
        color: #D97706;
    }

    .ticket-status.annulee {
        background: #FEE2E2;
        color: var(--rouge);
    }

    .btn-ticket {
        padding: 10px 20px;
        background: var(--rouge);
        color: white;
        border: none;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
        white-space: nowrap;
    }

    .btn-ticket:hover {
        background: var(--rouge-dark);
    }

    /* VIP Subscription View */
    .vip-card {
        background: linear-gradient(135deg, var(--or-bg), #FFF9E6);
        border: 2px solid var(--or);
        border-radius: var(--radius);
        padding: 32px;
        text-align: center;
    }

    .vip-card-header {
        margin-bottom: 24px;
    }

    .vip-badge-large {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--or);
        color: white;
        padding: 8px 16px;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 700;
    }

    .vip-card h2 {
        font-family: 'Eras Medium ITC', serif;
        font-size: 24px;
        color: var(--texte);
        margin: 16px 0 8px;
    }

    .vip-card .vip-date {
        font-size: 14px;
        color: var(--texte-doux);
    }

    .vip-days {
        font-size: 48px;
        font-weight: 800;
        color: var(--rouge);
        margin: 24px 0;
    }

    .vip-days span {
        font-size: 18px;
        font-weight: 500;
        color: var(--texte-doux);
    }

    .vip-progress {
        height: 8px;
        background: #E0E0E0;
        border-radius: 4px;
        margin: 24px 0;
        overflow: hidden;
    }

    .vip-progress-bar {
        height: 100%;
        background: var(--rouge);
        border-radius: 4px;
    }

    .btn-renew {
        padding: 14px 32px;
        background: var(--rouge);
        color: white;
        border: none;
        border-radius: 40px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .btn-renew:hover {
        background: var(--rouge-dark);
    }

    /* Non-VIP subscription page */
    .sub-advantages {
        display: grid;
        gap: 16px;
        margin-bottom: 32px;
    }

    .advantage-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: var(--gris-bg);
        border-radius: var(--radius-sm);
    }

    .advantage-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--or-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .advantage-icon svg {
        width: 20px;
        height: 20px;
        color: var(--or);
    }

    .advantage-text {
        font-size: 14px;
        font-weight: 600;
        color: var(--texte);
    }

    .sub-price {
        font-size: 32px;
        font-weight: 800;
        color: var(--rouge);
        margin-bottom: 8px;
    }

    .sub-price span {
        font-size: 14px;
        font-weight: 500;
        color: var(--texte-doux);
    }

    .payment-methods {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 24px;
    }

    .payment-card {
        padding: 16px;
        background: white;
        border: 2px solid var(--gris-border);
        border-radius: var(--radius-sm);
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .payment-card:hover {
        border-color: var(--rouge);
    }

    .payment-card.selected {
        border-color: var(--rouge);
        background: #FFF5F5;
    }

    .payment-card img {
        height: 32px;
        margin-bottom: 8px;
    }

    .payment-card span {
        font-size: 12px;
        color: var(--texte-doux);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 16px;
    }

    .empty-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 16px;
        background: var(--rose);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon svg {
        width: 28px;
        height: 28px;
        color: var(--rouge);
    }

    .empty-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--texte);
        margin-bottom: 8px;
    }

    .empty-desc {
        font-size: 13px;
        color: var(--texte-doux);
        margin-bottom: 16px;
    }

    /* Pagination */
    .pagination {
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .dashboard-container {
            grid-template-columns: 1fr;
        }

        .dashboard-sidebar {
            position: static;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .profile-grid {
            grid-template-columns: 1fr;
        }

        .payment-methods {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .vip-banner {
            flex-direction: column;
            text-align: center;
        }

        .event-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .event-thumb, .event-thumb-placeholder {
            width: 100%;
            height: 120px;
        }

        .event-actions {
            width: 100%;
        }

        .btn-action {
            flex: 1;
            text-align: center;
        }

        .ticket-card {
            flex-direction: column;
        }

        .ticket-img, .ticket-img-placeholder {
            width: 100%;
            height: 120px;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-page">
    <div class="dashboard-container">

        {{-- SIDEBAR --}}
        <aside class="dashboard-sidebar">
            {{-- Avatar & Info --}}
            @if($user->avatar)
                <img src="{{ $user->avatarUrl }}" alt="{{ $user->name }}" class="sidebar-avatar">
            @else
                <div class="sidebar-avatar">
                    {{ implode('', array_map(fn($n) => $n[0], explode(' ', $user->name))) }}
                </div>
            @endif

            <h2 class="sidebar-name">{{ $user->name }}</h2>

            @if($user->isVip())
                <span class="sidebar-badge vip">
                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    Membre VIP
                </span>
            @else
                <span class="sidebar-badge membre">Membre</span>
            @endif

            <p class="sidebar-email">{{ $user->email }}</p>
            <p class="sidebar-date">Membre depuis {{ $user->created_at->translatedFormat('F Y') }}</p>

            <div class="sidebar-divider"></div>

            {{-- Navigation --}}
            <nav class="sidebar-nav">
                <a href="{{ route('user.profile', ['section' => 'dashboard']) }}" class="sidebar-link {{ $section === 'dashboard' ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Tableau de bord
                </a>

                <a href="{{ route('user.profile', ['section' => 'profil']) }}" class="sidebar-link {{ $section === 'profil' ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Mon profil
                </a>

                <a href="{{ route('user.profile', ['section' => 'events']) }}" class="sidebar-link {{ $section === 'events' ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Mes evenements
                </a>

                <a href="{{ route('user.profile', ['section' => 'tickets']) }}" class="sidebar-link {{ $section === 'tickets' ? 'active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    Mes billets
                </a>

                <a href="{{ route('user.profile', ['section' => 'vip']) }}" class="sidebar-link {{ $section === 'vip' ? 'active' : '' }}">
                    @if($user->isVip())
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    @else
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    @endif
                    Abonnement VIP
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-link logout" style="width: 100%; border: none; background: transparent; cursor: pointer;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Deconnexion
                    </button>
                </form>
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="dashboard-content">

            {{-- ===== DASHBOARD SECTION ===== }}
            @if($section === 'dashboard')
                {{-- Stats --}}
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon red">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $stats['events_created'] }}</div>
                            <div class="stat-label">Evenements créés</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon red">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $stats['participations'] }}</div>
                            <div class="stat-label">Participations</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon red">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $stats['tickets_sold'] }}</div>
                            <div class="stat-label">Billets vendus</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon {{ $user->isVip() ? 'gold' : 'gray' }}">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $user->isVip() ? 'VIP Actif' : 'Non VIP' }}</div>
                            <div class="stat-label">{{ $user->isVip() ? $vipDaysRemaining . ' jours restants' : 'Statut abonnement' }}</div>
                        </div>
                    </div>
                </div>

                {{-- VIP Banner (if not VIP) --}}
                @if(!$user->isVip())
                    <div class="vip-banner">
                        <div class="vip-banner-text">
                            <h3>Debloquez la Marketplace</h3>
                            <p>Publiez vos produits et services, et plus encore!</p>
                        </div>
                        <a href="{{ route('vip.subscribe.show') }}" class="btn-vip primary">Devenir VIP</a>
                    </div>
                @endif

                {{-- Recent Activity --}}
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Activite recente
                        </h2>
                        <a href="{{ route('user.profile', ['section' => 'events']) }}" class="section-link">Voir tout</a>
                    </div>

                    @if($myEvents->count() > 0)
                        <div class="activity-list">
                            @foreach($myEvents->take(3) as $event)
                                <div class="activity-item">
                                    @if($event->image_couverture)
                                        <img src="{{ Storage::url($event->image_couverture) }}" alt="{{ $event->titre }}" class="activity-img">
                                    @else
                                        <div class="activity-img-placeholder">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="activity-info">
                                        <h3 class="activity-title">{{ $event->titre }}</h3>
                                        <div class="activity-meta">
                                            <span>{{ $event->date->translatedFormat('d M Y') }}</span>
                                            <span class="activity-status {{ $event->statut }}">
                                                {{ $event->statut === 'publie' ? 'Publie' : 'Brouillon' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="empty-title">Aucun evenement cree recemment</p>
                            <a href="{{ route('events.create') }}" class="btn-vip primary">Creer un evenement</a>
                        </div>
                    @endif
                </div>

                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                            Mes reservations recentes
                        </h2>
                        <a href="{{ route('user.profile', ['section' => 'tickets']) }}" class="section-link">Voir tout</a>
                    </div>

                    @if($myBookings->count() > 0)
                        <div class="activity-list">
                            @foreach($myBookings->take(3) as $booking)
                                <div class="activity-item">
                                    @if($booking->event && $booking->event->image_couverture)
                                        <img src="{{ Storage::url($booking->event->image_couverture) }}" alt="{{ $booking->event->titre }}" class="activity-img">
                                    @else
                                        <div class="activity-img-placeholder">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="activity-info">
                                        @if($booking->event)
                                            <h3 class="activity-title">{{ $booking->event->titre }}</h3>
                                            <div class="activity-meta">
                                                <span>{{ $booking->event->date->translatedFormat('d M Y') }}</span>
                                                <span class="activity-status {{ $booking->status }}">
                                                    @if($booking->status === 'confirmee') Confirme
                                                    @elseif($booking->status === 'en_attente') En attente
                                                    @else Annule
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="empty-title">Aucune reservation recente</p>
                            <a href="{{ route('events.index') }}" class="btn-vip primary">Decouvrir les evenements</a>
                        </div>
                    @endif
                </div>
            @endif

            {{-- ===== PROFIL SECTION ===== }}
            @if($section === 'profil')
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Informations personnelles</h2>
                        <a href="{{ route('profile.edit') }}" class="btn-modify">Modifier</a>
                    </div>

                    <div class="profile-grid">
                        <div class="profile-field">
                            <span class="profile-label">Nom complet</span>
                            <span class="profile-value">{{ $user->name }}</span>
                        </div>

                        <div class="profile-field">
                            <span class="profile-label">Email</span>
                            <span class="profile-value">
                                {{ $user->email }}
                                @if($user->email_verified_at)
                                    <svg class="verified-icon verified" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @else
                                    <svg class="verified-icon unverified" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                @endif
                            </span>
                        </div>

                        <div class="profile-field">
                            <span class="profile-label">Telephone</span>
                            <span class="profile-value">{{ $user->phone ?? 'Non renseigne' }}</span>
                        </div>

                        <div class="profile-field">
                            <span class="profile-label">Pays</span>
                            <span class="profile-value">{{ $user->country ?? 'Non renseigne' }}</span>
                        </div>

                        <div class="profile-field">
                            <span class="profile-label">Membre depuis</span>
                            <span class="profile-value">{{ $user->created_at->translatedFormat('F Y') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ===== EVENTS SECTION ===== }}
            @if($section === 'events')
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Mes evenements</h2>
                        <a href="{{ route('events.create') }}" class="btn-create">Creer un evenement</a>
                    </div>

                    @if($allMyEvents->count() > 0)
                        <div class="events-list">
                            @foreach($allMyEvents as $event)
                                <div class="event-row">
                                    @if($event->image_couverture)
                                        <img src="{{ Storage::url($event->image_couverture) }}" alt="{{ $event->titre }}" class="event-thumb">
                                    @else
                                        <div class="event-thumb-placeholder">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="event-details">
                                        <h3 class="event-title">{{ $event->titre }}</h3>
                                        <div class="event-meta">
                                            <span>{{ $event->date->translatedFormat('d M Y') }}</span>
                                            @if($event->category)
                                                <span class="event-badge" style="background: var(--rose); color: var(--rouge);">
                                                    {{ $event->category->nom }}
                                                </span>
                                            @endif
                                            <span>{{ $event->bookings_count }} reservation(s)</span>
                                            <span class="event-badge {{ $event->statut }}">
                                                @if($event->statut === 'publie') Publie
                                                @elseif($event->statut === 'brouillon') Brouillon
                                                @else Complet
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="event-actions">
                                        <a href="{{ route('events.show', $event->slug) }}" class="btn-action view">Voir</a>
                                        <a href="#" class="btn-action edit">Modifier</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="pagination">
                            {{ $allMyEvents->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="empty-title">Vous n'avez pas encore cree d'evenement</p>
                            <p class="empty-desc">Partagez vos evenements avec la communaute Eledji</p>
                            <a href="{{ route('events.create') }}" class="btn-vip primary">Creer mon premier evenement</a>
                        </div>
                    @endif
                </div>
            @endif

            {{-- ===== TICKETS SECTION ===== }}
            @if($section === 'tickets')
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Mes billets</h2>
                    </div>

                    @if($allMyBookings->count() > 0)
                        <div class="activity-list">
                            @foreach($allMyBookings as $booking)
                                <div class="ticket-card {{ $booking->status === 'annulee' ? 'cancelled' : '' }}">
                                    @if($booking->event && $booking->event->image_couverture)
                                        <img src="{{ Storage::url($booking->event->image_couverture) }}" alt="{{ $booking->event->titre }}" class="ticket-img">
                                    @else
                                        <div class="ticket-img-placeholder">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="ticket-info">
                                        @if($booking->event)
                                            <h3 class="ticket-title">{{ $booking->event->titre }}</h3>
                                            <div class="ticket-meta">
                                                <span>{{ $booking->event->date->translatedFormat('d M Y') }}</span>
                                                @if($booking->event->lieu)
                                                    <span>{{ $booking->event->lieu }}</span>
                                                @endif
                                            </div>
                                        @endif
                                        <span class="ticket-number">{{ $booking->numero_reservation }}</span>
                                        <span class="ticket-status {{ $booking->status }}">
                                            @if($booking->status === 'confirmee') Confirme
                                            @elseif($booking->status === 'en_attente') En attente
                                            @else Annule
                                            @endif
                                        </span>
                                    </div>

                                    @if($booking->status === 'confirmee')
                                        <a href="{{ route('booking.success', $booking) }}" class="btn-ticket">Voir le billet</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="pagination">
                            {{ $allMyBookings->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                            </div>
                            <p class="empty-title">Vous n'avez encore participe a aucun evenement</p>
                            <p class="empty-desc">Decouvrez les evenements pres de chez vous</p>
                            <a href="{{ route('events.index') }}" class="btn-vip primary">Decouvrir les evenements</a>
                        </div>
                    @endif
                </div>
            @endif

            {{-- ===== VIP SECTION ===== }}
            @if($section === 'vip')
                @if($user->isVip())
                    <div class="vip-card">
                        <div class="vip-card-header">
                            <span class="vip-badge-large">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                VIP Actif
                            </span>
                            <h2>Marketplace VIP Eledji</h2>
                            <p class="vip-date">Active depuis {{ $user->vip_subscribed_at->translatedFormat('d M Y') }}</p>
                            <p class="vip-date">Expire le {{ $user->vip_expires_at->translatedFormat('d M Y') }}</p>
                        </div>

                        <div class="vip-days">
                            {{ $vipDaysRemaining }} <span>jours restants</span>
                        </div>

                        <div class="vip-progress">
                            @php
                                $progress = 100 - ($vipDaysRemaining / $vipDuration * 100);
                                $progress = max(0, min(100, $progress));
                            @endphp
                            <div class="vip-progress-bar" style="width: {{ $progress }}%"></div>
                        </div>

                        <a href="{{ route('vip.subscribe.show') }}" class="btn-renew">Renouveler</a>
                    </div>
                @else
                    <div class="content-section">
                        <div class="section-header">
                            <h2 class="section-title">Abonnement VIP</h2>
                        </div>

                        <div class="sub-advantages">
                            <div class="advantage-item">
                                <div class="advantage-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="advantage-text">Publiez vos produits sur la Marketplace</span>
                            </div>
                            <div class="advantage-item">
                                <div class="advantage-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="advantage-text">Badge VIP visible sur votre profil</span>
                            </div>
                            <div class="advantage-item">
                                <div class="advantage-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="advantage-text">Acces aux evenements VIP</span>
                            </div>
                        </div>

                        <p class="sub-price">{{ number_format($vipPrice, 0, ',', ' ') }} XOF <span>/ {{ $vipDuration }} jours</span></p>

                        <a href="{{ route('vip.subscribe.show') }}" class="btn-vip primary" style="display: block; text-align: center; max-width: 300px; margin: 0 auto;">Devenir VIP</a>
                    </div>
                @endif
            @endif

        </main>
    </div>
</div>
@endsection