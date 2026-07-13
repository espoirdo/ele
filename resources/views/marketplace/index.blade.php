@extends('layouts.app')

@section('title', 'Marketplace - ELEDJI')

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
    --vert-bg: #E8F5E9;
    --vert: #22C55E;
}
*, *::before, *::after { box-sizing: border-box; }

.marketplace-page {
    min-height: calc(100vh - 80px);
    padding: 56px 24px 60px;
    background: var(--gris-bg);
    font-family: var(--poppins);
}

.marketplace-container {
    max-width: 1100px;
    margin: 0 auto;
}

/* Header banner */
.marketplace-header {
    background: linear-gradient(135deg, var(--or), #E09000);
    border-radius: var(--radius);
    padding: 32px;
    margin-bottom: 32px;
    display: flex;
    align-items: center;
    gap: 20px;
}

.marketplace-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: white;
    color: var(--or);
    padding: 8px 16px;
    border-radius: 24px;
    font-size: 14px;
    font-weight: 700;
}

.marketplace-title {
    font-family: 'Eras Medium ITC', serif;
    font-size: 28px;
    font-weight: 700;
    color: white;
    margin: 0;
}

.marketplace-subtitle {
    font-size: 15px;
    color: rgba(255,255,255,0.85);
    margin: 8px 0 0;
}

/* VIP Banner */
.vip-banner {
    border-radius: var(--radius);
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.vip-banner-vip {
    background: var(--vert-bg);
    border: 1px solid var(--vert);
}

.vip-banner-non-vip {
    background: var(--or-bg);
    border: 1px solid var(--or);
}

.vip-banner-text {
    font-size: 15px;
    font-weight: 600;
    color: var(--texte);
}

.vip-banner-text span {
    font-weight: 700;
}

.btn-banner {
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-banner-vip {
    background: var(--rouge);
    color: white;
    border: none;
}

.btn-banner-vip:hover {
    background: var(--rouge-dark);
}

.btn-banner-non-vip {
    background: var(--or);
    color: white;
    border: none;
}

.btn-banner-non-vip:hover {
    background: #E09000;
}

/* Grid */
.marketplace-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}

/* Listing card */
.listing-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    transition: all 0.25s ease;
}

.listing-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
}

.listing-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
    background: linear-gradient(135deg, var(--rose), var(--gris-bg));
}

.listing-image-placeholder {
    width: 100%;
    height: 180px;
    background: linear-gradient(135deg, var(--or-bg), var(--rose));
    display: flex;
    align-items: center;
    justify-content: center;
}

.listing-image-placeholder svg {
    width: 48px;
    height: 48px;
    color: var(--or);
    opacity: 0.6;
}

.listing-content {
    padding: 20px;
}

.listing-title {
    font-family: 'Eras Medium ITC', serif;
    font-size: 16px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 8px;
    line-height: 1.4;
}

.listing-description {
    font-size: 13px;
    color: var(--texte-doux);
    margin: 0 0 12px;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.listing-price {
    font-size: 20px;
    font-weight: 800;
    color: var(--rouge);
    margin-bottom: 12px;
}

.listing-seller {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--gris-border);
}

.seller-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--rose);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    color: var(--rouge);
}

.seller-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--texte);
}

.btn-contact {
    width: 100%;
    padding: 12px;
    background: var(--rouge);
    color: white;
    border: none;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
    display: block;
    text-align: center;
}

.btn-contact:hover {
    background: var(--rouge-dark);
}

.btn-contact-outline {
    width: 100%;
    padding: 12px;
    background: transparent;
    color: var(--rouge);
    border: 2px solid var(--rouge);
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
    display: block;
    text-align: center;
}

.btn-contact-outline:hover {
    background: var(--rouge);
    color: white;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 60px 24px;
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: var(--or-bg);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-state-icon svg {
    width: 36px;
    height: 36px;
    color: var(--or);
}

.empty-state-title {
    font-family: 'Eras Medium ITC', serif;
    font-size: 20px;
    font-weight: 700;
    color: var(--texte);
    margin: 0 0 8px;
}

.empty-state-desc {
    font-size: 14px;
    color: var(--texte-doux);
    margin: 0;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 32px;
    display: flex;
    justify-content: center;
}

@media (max-width: 640px) {
    .marketplace-header {
        flex-direction: column;
        text-align: center;
        padding: 24px;
        margin-top: 8px;
    }

    .marketplace-title {
        font-size: 22px;
    }

    .marketplace-grid {
        grid-template-columns: 1fr;
    }

    .vip-banner {
        flex-direction: column;
        text-align: center;
    }
}
</style>
@endpush

@section('content')
<div class="marketplace-page">
    <div class="marketplace-container">

        <div class="marketplace-header">
            <div class="marketplace-badge">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                VIP
            </div>
            <div>
                <h1 class="marketplace-title">Marketplace Eledji</h1>
                <p class="marketplace-subtitle">Découvrez les services et produits exclusifs de nos organisateurs</p>
            </div>
        </div>

        {{-- Bannières conditionnelles --}}
        @auth
            @if($isVip)
                <div class="vip-banner vip-banner-vip">
                    <span class="vip-banner-text">Vous êtes membre VIP — vous pouvez <strong>publier vos produits</strong> sur la Marketplace</span>
                    <a href="{{ route('marketplace.create') }}" class="btn-banner btn-banner-vip">Publier un produit</a>
                </div>
            @else
                <div class="vip-banner vip-banner-non-vip">
                    <span class="vip-banner-text">Vous voulez vendre sur la Marketplace ? <strong>Devenez membre VIP Eledji</strong></span>
                    <a href="{{ route('vip.subscribe.show') }}" class="btn-banner btn-banner-non-vip">Devenir VIP</a>
                </div>
            @endif
        @endauth

        @if($listings->count() > 0)
            <div class="marketplace-grid">
                @foreach($listings as $listing)
                    <div class="listing-card">
                        @if($listing->image)
                            <img src="{{ $listing->imageUrl }}" alt="{{ $listing->titre }}" class="listing-image">
                        @else
                            <div class="listing-image-placeholder">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="listing-content">
                            <h3 class="listing-title">{{ $listing->titre }}</h3>
                            <p class="listing-description">{{ $listing->description }}</p>
                            <p class="listing-price">{{ number_format($listing->prix, 0, ',', ' ') }} XOF</p>
                            <div class="listing-seller">
                                @if($listing->user->avatar)
                                    <img src="{{ $listing->user->avatarUrl }}" alt="{{ $listing->user->name }}" class="seller-avatar" style="object-fit: cover;">
                                @else
                                    <div class="seller-avatar">
                                        {{ implode('', array_map(fn($n) => $n[0], explode(' ', $listing->user->name))) }}
                                    </div>
                                @endif
                                <span class="seller-name">{{ $listing->user->name }}</span>
                            </div>
                            @auth
                                <a href="mailto:{{ $listing->user->email }}?subject=Interest in {{ urlencode($listing->titre) }} - Marketplace Eledji" class="btn-contact">
                                    Contacter le vendeur
                                </a>
                            @else
                                <a href="{{ route('login').'?redirect='.url()->current() }}" class="btn-contact-outline">
                                    Connectez-vous pour contacter
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrapper">
                {{ $listings->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h2 class="empty-state-title">La Marketplace arrive bientôt</h2>
                <p class="empty-state-desc">Restez connecté - de nouvelles offres exclusives arrive</p>
            </div>
        @endif

    </div>
</div>
@endsection