@extends('layouts.app')
@section('title', $event->titre . ' - ELEDJI')

@push('styles')
<style>
:root {
    --rouge: #CC0000;
    --rouge-dark: #910000;
    --rose: #F7D6D3;
    --rose-pale: #FDF0F0;
    --gris-bg: #F9F9F9;
    --gris-border: #EBEBEB;
    --texte: #1a1a1a;
    --texte-doux: #666;
    --poppins: 'Poppins', sans-serif;
    --radius: 12px;
    --radius-sm: 10px;
    --shadow-sm: 0 4px 20px rgba(0, 0, 0, 0.07);
    --shadow-md: 0 8px 30px rgba(0, 0, 0, 0.12);
}
*, *::before, *::after { box-sizing: border-box; }

.detail-hero {
    width: 100%;
    height: 480px;
    position: relative;
    overflow: hidden;
    margin-top: 0;
}
.detail-hero img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 8s ease;
}
.detail-hero:hover img { transform: scale(1.04); }
.detail-hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(
        to top,
        rgba(0, 0, 0, 0.75) 0%,
        rgba(0, 0, 0, 0.20) 50%,
        transparent 100%
    );
}
.detail-breadcrumb {
    position: absolute;
    top: 28px; left: 50%;
    transform: translateX(-50%);
    width: 100%; max-width: 1100px;
    padding: 0 28px;
    display: flex; align-items: center; gap: 8px;
    color: rgba(255, 255, 255, 0.75);
    font-size: 13px; font-weight: 500;
    font-family: var(--poppins);
    z-index: 2;
}
.detail-breadcrumb a {
    color: rgba(255, 255, 255, 0.75);
    text-decoration: none;
    transition: color 0.25s ease;
}
.detail-breadcrumb a:hover { color: white; }
.detail-breadcrumb span { color: rgba(255, 255, 255, 0.4); }
.detail-hero-badge {
    position: absolute;
    bottom: 28px; left: 50%;
    transform: translateX(-50%);
    width: 100%; max-width: 1100px;
    padding: 0 28px;
    z-index: 2;
    display: flex; align-items: center; gap: 12px;
}
.detail-cat-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--rouge);
    color: white;
    font-size: 12px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    padding: 6px 16px;
    border-radius: 40px;
}
.detail-gratuit-badge {
    display: inline-flex; align-items: center;
    background: rgba(52, 199, 89, 0.9);
    color: white;
    font-size: 12px; font-weight: 700;
    padding: 6px 14px; border-radius: 40px;
}

.detail-container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 28px;
    font-family: var(--poppins);
}
.detail-page { background: var(--gris-bg); min-height: 60vh; padding-bottom: 80px; }

.detail-title-bar {
    background: white;
    border-bottom: 1px solid var(--gris-border);
    padding: 28px 0 20px;
    margin-bottom: 32px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
}
.detail-title-inner {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 28px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}
.detail-title-left h1 {
    font-size: 26px; font-weight: 800;
    color: var(--texte);
    margin: 0 0 8px 0; line-height: 1.2;
}
.detail-rating {
    display: flex; align-items: center; gap: 8px;
}
.detail-stars { color: #FBBF24; font-size: 16px; letter-spacing: 1px; }
.detail-rating-num { font-weight: 700; font-size: 16px; color: var(--texte); }
.detail-rating-count { font-size: 13px; color: var(--texte-doux); }
.detail-action-btns {
    display: flex; gap: 10px; align-items: center;
    flex-shrink: 0;
}
.detail-action-btn {
    width: 42px; height: 42px;
    border-radius: var(--radius-sm);
    background: var(--gris-bg);
    border: 1.5px solid var(--gris-border);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: all 0.25s ease;
    color: #555;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}
.detail-action-btn:hover {
    background: white;
    box-shadow: var(--shadow-sm);
    transform: translateY(-2px);
}
.detail-action-btn.like:hover { background: var(--rose-pale); border-color: var(--rouge); color: var(--rouge); }
.detail-action-btn.like:hover svg { stroke: var(--rouge); }

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 28px;
    align-items: start;
}
@media (max-width: 900px) {
    .detail-grid { grid-template-columns: 1fr; }
    .detail-sticky { position: static !important; }
}

.detail-card {
    background: white;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 24px;
    transition: box-shadow 0.3s;
}
.detail-card:hover { box-shadow: var(--shadow-md); }
.detail-card:last-child { margin-bottom: 0; }
.detail-card-header {
    padding: 20px 22px 16px;
    border-bottom: 1px solid var(--gris-border);
    display: flex; align-items: center; gap: 10px;
}
.detail-card-icon {
    width: 36px; height: 36px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.detail-card-title {
    font-size: 15px; font-weight: 700;
    color: var(--texte); margin: 0;
}
.detail-card-body { padding: 20px 22px; }

.detail-desc-text {
    font-size: 14px;
    color: var(--texte-doux);
    line-height: 1.85;
    margin: 0;
    white-space: pre-line;
}

.comment-list { display: flex; flex-direction: column; gap: 0; }
.comment-item {
    display: flex; gap: 12px;
    padding: 16px 0;
    border-bottom: 1px solid var(--gris-border);
}
.comment-item:last-of-type { border-bottom: none; }
.comment-avatar {
    width: 40px; height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: 700; font-size: 14px;
    flex-shrink: 0;
}
.comment-body { flex: 1; min-width: 0; }
.comment-top {
    display: flex; align-items: center;
    justify-content: space-between; margin-bottom: 4px;
}
.comment-name { font-weight: 700; font-size: 14px; color: var(--texte); }
.comment-stars { color: #FBBF24; font-size: 13px; letter-spacing: 1px; }
.comment-text { font-size: 13px; color: var(--texte-doux); line-height: 1.6; margin: 0 0 4px; }
.comment-time { font-size: 11px; color: #bbb; }
.comment-empty {
    text-align: center; padding: 28px 0;
    color: #ccc; font-size: 14px;
}
.comment-empty svg { margin: 0 auto 8px; display: block; opacity: 0.4; }

.comment-form { margin-top: 4px; }
.star-input-row {
    display: flex; gap: 4px; margin-bottom: 12px;
}
.star-input-row label { cursor: pointer; }
.star-input-row .star-btn {
    font-size: 26px;
    color: #ddd;
    transition: color 0.15s, transform 0.15s;
    display: inline-block;
}
.star-input-row .star-btn:hover { transform: scale(1.2); }
.comment-textarea {
    width: 100%;
    border: 1.5px solid var(--gris-border);
    border-radius: var(--radius-sm);
    padding: 12px 14px;
    font-size: 14px;
    font-family: var(--poppins);
    color: var(--texte);
    resize: none;
    transition: border-color 0.25s, box-shadow 0.25s;
    background: var(--gris-bg);
    outline: none;
}
.comment-textarea:focus {
    border-color: var(--rouge);
    box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.1);
    background: white;
}
.btn-comment {
    display: inline-flex; align-items: center; gap: 6px;
    margin-top: 10px;
    background: var(--rouge);
    color: white;
    border: none; border-radius: 40px;
    padding: 10px 22px;
    font-size: 13px; font-weight: 600;
    font-family: var(--poppins);
    cursor: pointer;
    transition: all 0.25s ease;
}
.btn-comment:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(204, 0, 0, 0.25);
}
.comment-login-prompt {
    text-align: center; padding: 16px;
    background: var(--rose-pale);
    border-radius: var(--radius-sm);
    margin-top: 12px;
}
.comment-login-prompt a {
    color: var(--rouge); font-weight: 600;
    font-size: 13px; text-decoration: none;
}
.comment-login-prompt a:hover { text-decoration: underline; }

.map-placeholder {
    height: 220px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    background: var(--gris-bg);
    color: #ccc; gap: 8px;
    font-size: 14px;
}
#detail-map { width: 100%; height: 220px; }

.event-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.event-meta-item {
    display: flex; align-items: center; gap: 10px;
    background: var(--gris-bg);
    border-radius: var(--radius-sm);
    padding: 12px 14px;
}
.event-meta-icon {
    width: 36px; height: 36px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.event-meta-label {
    font-size: 11px; color: #aaa;
    text-transform: uppercase; letter-spacing: 0.07em;
    font-weight: 600; display: block;
}
.event-meta-value {
    font-size: 13px; font-weight: 600;
    color: var(--texte); display: block;
    margin-top: 1px;
}

.detail-sticky {
    position: sticky;
    top: 90px;
}
.reservation-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}
.reservation-header {
    background: linear-gradient(135deg, var(--rouge-dark), var(--rouge));
    padding: 20px 22px;
    color: white;
}
.reservation-header h3 {
    font-size: 16px; font-weight: 700;
    margin: 0 0 4px;
}
.reservation-header p {
    font-size: 12px; opacity: 0.75;
    margin: 0;
}
.reservation-body { padding: 20px 22px; }

.ticket-btn {
    width: 100%;
    background: var(--rose-pale);
    border: 2px solid transparent;
    border-radius: var(--radius-sm);
    padding: 14px 16px;
    margin-bottom: 10px;
    display: flex; align-items: center;
    justify-content: space-between;
    cursor: pointer;
    transition: all 0.25s ease;
    font-family: var(--poppins);
    text-align: left;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}
.ticket-btn:hover {
    border-color: rgba(204, 0, 0, 0.3);
    background: #fce8e8;
    transform: translateX(2px);
}
.ticket-btn.selected {
    border-color: var(--rouge);
    background: #fce8e8;
    box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.12);
}
.ticket-name { font-size: 14px; font-weight: 600; color: var(--texte); }
.ticket-dispo { font-size: 11px; color: #aaa; margin-top: 2px; }
.ticket-price {
    font-size: 15px; font-weight: 700;
    color: var(--rouge-dark);
}
.ticket-check {
    width: 20px; height: 20px;
    border-radius: 50%;
    border: 2px solid #ddd;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
}
.ticket-btn.selected .ticket-check {
    background: var(--rouge);
    border-color: var(--rouge);
    color: white;
}

.reservation-total {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 14px 0;
    border-top: 1.5px solid var(--gris-border);
    border-bottom: 1.5px solid var(--gris-border);
    margin: 6px 0 16px;
}
.reservation-total-label {
    font-size: 14px; font-weight: 600; color: var(--texte-doux);
}
.reservation-total-amount {
    font-size: 22px; font-weight: 800; color: var(--texte);
}

.btn-acheter {
    width: 100%;
    padding: 15px;
    border: none; border-radius: var(--radius-sm);
    font-size: 15px; font-weight: 700;
    font-family: var(--poppins);
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-acheter.active {
    background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
    color: white;
    box-shadow: 0 6px 24px rgba(204, 0, 0, 0.35);
}
.btn-acheter.active:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 32px rgba(204, 0, 0, 0.4);
}
.btn-acheter.active:active { transform: scale(0.98); }
.btn-acheter.disabled {
    background: #e8e8e8; color: #aaa; cursor: not-allowed;
}
.btn-acheter.login {
    background: white;
    color: var(--rouge);
    border: 2px solid var(--rouge);
    text-decoration: none;
}
.btn-acheter.login:hover { background: var(--rose-pale); }

.reservation-infos {
    margin-top: 18px;
    padding-top: 16px;
    border-top: 1px solid var(--gris-border);
    display: flex; flex-direction: column; gap: 10px;
}
.res-info-item {
    display: flex; align-items: center; gap: 10px;
    font-size: 13px; color: var(--texte-doux);
}
.res-info-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    background: var(--gris-bg);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.reservation-guarantees {
    padding: 14px 22px;
    background: var(--gris-bg);
    border-top: 1px solid var(--gris-border);
    display: flex; align-items: center; gap: 8px;
    font-size: 12px; color: #aaa;
}

.similar-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-top: 12px;
}
@media (max-width: 700px) { .similar-grid { grid-template-columns: 1fr 1fr; } }
.similar-card {
    display: block; text-decoration: none;
    border-radius: var(--radius-sm);
    overflow: hidden;
    background: white;
    border: 1px solid var(--gris-border);
    transition: all 0.25s ease;
}
.similar-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}
.similar-card-img {
    height: 120px; overflow: hidden; position: relative;
}
.similar-card-img img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 0.5s ease;
}
.similar-card:hover .similar-card-img img { transform: scale(1.08); }
.similar-card-body { padding: 10px 12px; }
.similar-card-title {
    font-size: 13px; font-weight: 700;
    color: var(--texte); margin: 0 0 4px;
    line-height: 1.3;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.similar-card-date { font-size: 11px; color: #aaa; }

.section-label-sm {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.1em;
    color: var(--rouge); margin: 0 0 4px;
    display: block;
}
.section-title-sm {
    font-size: 18px; font-weight: 800;
    color: var(--texte); margin: 0 0 20px;
}
</style>
@endpush

@section('content')

<div class="detail-hero" id="hero-img">
    <img src="{{ $event->image_url }}" alt="{{ $event->titre }}" id="hero-photo">
    <div class="detail-hero-overlay"></div>

    <div class="detail-breadcrumb">
        <a href="{{ route('home') }}">Accueil</a>
        <span>-</span>
        <a href="{{ route('events.index') }}">Evenements</a>
        <span>-</span>
        <span style="color:white">{{ Str::limit($event->titre, 30) }}</span>
    </div>

    <div class="detail-hero-badge">
        @if($event->category)
            <span class="detail-cat-badge">
                {{ $event->category->nom }}
            </span>
        @endif
        @if($event->est_gratuit)
            <span class="detail-gratuit-badge">Entree libre</span>
        @endif
    </div>
</div>

<div class="detail-title-bar">
    <div class="detail-title-inner">
        <div class="detail-title-left">
            <h1>{{ $event->titre }}</h1>
            <div class="detail-rating">
                <div class="detail-stars">
                    @php $note = round($event->note_moyenne ?? 0); @endphp
                    @for($i=1; $i<=5; $i++)
                        @if($i <= $note)<svg width="16" height="16" fill="#FBBF24" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@else<svg width="16" height="16" fill="#ddd" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endif
                    @endfor
                </div>
                <span class="detail-rating-num">
                    {{ number_format($event->note_moyenne ?? 0, 1) }}
                </span>
                <span class="detail-rating-count">
                    ({{ $event->comments->count() }} avis)
                </span>
            </div>
        </div>
        <div class="detail-action-btns">
            <button class="detail-action-btn"
                    onclick="if(navigator.share) navigator.share({title:'{{ addslashes($event->titre) }}', url: window.location.href})"
                    title="Partager">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
            </button>
            <button class="detail-action-btn like" title="Ajouter aux favoris" id="like-btn">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" id="like-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<div class="detail-page">
    <div class="detail-container" style="padding-top:32px">
        <div class="detail-grid">

            <div>

                <div class="detail-card" data-gsap="fade-up">
                    <div class="detail-card-header">
                        <div class="detail-card-icon" style="background: rgba(204, 0, 0, 0.1)">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#CC0000" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="detail-card-title">Informations</h3>
                    </div>
                    <div class="detail-card-body" style="padding-top:16px">
                        <div class="event-meta">
                            <div class="event-meta-item">
                                <div class="event-meta-icon" style="background: rgba(0, 122, 255, 0.1)">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#007AFF" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="event-meta-label">Date</span>
                                    <span class="event-meta-value">{{ $event->date->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>
                            <div class="event-meta-item">
                                <div class="event-meta-icon" style="background: rgba(255, 149, 0, 0.1)">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#FF9500" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="event-meta-label">Heure</span>
                                    <span class="event-meta-value">{{ $event->heure }}</span>
                                </div>
                            </div>
                            <div class="event-meta-item">
                                <div class="event-meta-icon" style="background: rgba(52, 199, 89, 0.1)">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#34C759" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="event-meta-label">Lieu</span>
                                    <span class="event-meta-value">{{ Str::limit($event->lieu, 22) }}</span>
                                </div>
                            </div>
                            <div class="event-meta-item">
                                <div class="event-meta-icon" style="background: rgba(175, 82, 222, 0.1)">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#AF52DE" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="event-meta-label">Categorie</span>
                                    <span class="event-meta-value">{{ $event->category?->nom ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detail-card" data-gsap="fade-up">
                    <div class="detail-card-header">
                        <div class="detail-card-icon" style="background: rgba(255, 149, 0, 0.1)">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#FF9500" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                        </div>
                        <h3 class="detail-card-title">Description</h3>
                    </div>
                    <div class="detail-card-body">
                        <p class="detail-desc-text">{{ $event->description }}</p>
                    </div>
                </div>

                <div class="detail-card" data-gsap="fade-up">
                    <div class="detail-card-header">
                        <div class="detail-card-icon" style="background: rgba(52, 199, 89, 0.1)">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#34C759" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="detail-card-title">Localisation - {{ $event->lieu }}</h3>
                    </div>
                    @if($event->latitude && $event->longitude)
                        <div id="detail-map"></div>
                    @else
                        <div class="map-placeholder">
                            <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span>Carte interactive</span>
                        </div>
                    @endif
                </div>

                <div class="detail-card" data-gsap="fade-up">
                    <div class="detail-card-header">
                        <div class="detail-card-icon" style="background: rgba(0, 122, 255, 0.1)">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#007AFF" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <h3 class="detail-card-title">
                            Avis
                            @if($event->comments->count() > 0)
                                <span style="font-weight:400;color:#aaa;font-size:13px">
                                    ({{ $event->comments->count() }})
                                </span>
                            @endif
                        </h3>
                    </div>
                    <div class="detail-card-body">

                        @forelse($event->comments as $comment)
                            <div class="comment-item">
                                <div class="comment-avatar">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                                <div class="comment-body">
                                    <div class="comment-top">
                                        <span class="comment-name">{{ $comment->user->name }}</span>
                                        <span class="comment-stars">
                                            @for($i=1; $i<=5; $i++)
                                                @if($i <= $comment->note)<svg width="12" height="12" fill="#FBBF24" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@else<svg width="12" height="12" fill="#ddd" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endif
                                            @endfor
                                        </span>
                                    </div>
                                    <p class="comment-text">{{ $comment->contenu }}</p>
                                    <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="comment-empty">
                                <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Aucun avis pour le moment. Soyez le premier !
                            </div>
                        @endforelse

                        @auth
                            <form action="{{ route('comments.store', $event) }}"
                                  method="POST" class="comment-form"
                                  style="margin-top:{{ $event->comments->count() > 0 ? '16px' : '0' }};
                                         padding-top:{{ $event->comments->count() > 0 ? '16px' : '0' }};
                                         border-top:{{ $event->comments->count() > 0 ? '1px solid #ebebeb' : 'none' }}">
                                @csrf
                                <p style="font-size:13px;font-weight:600;color:#555;margin:0 0 8px">
                                    Votre note :
                                </p>
                                <div class="star-input-row" id="star-rating">
                                    @for($i=1; $i<=5; $i++)
                                        <label>
                                            <input type="radio" name="note" value="{{ $i }}"
                                                   class="sr-only" required>
                                            <span class="star-btn" data-star="{{ $i }}">&#9733;</span>
                                        </label>
                                    @endfor
                                </div>
                                <textarea name="contenu" rows="3"
                                          placeholder="Partagez votre experience avec cet evenement..."
                                          class="comment-textarea" required></textarea>
                                <button type="submit" class="btn-comment">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    Publier
                                </button>
                            </form>
                        @else
                            <div class="comment-login-prompt">
                                <a href="{{ route('login') }}">
                                    Connectez-vous pour laisser un avis
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>

            </div>

            <div class="detail-sticky"
                 x-data="{
                     selectedTicket: null,
                     get total() { return this.selectedTicket ? this.selectedTicket.prix : 0; }
                 }">

                <div class="reservation-card" data-gsap="fade-up">

                    <div class="reservation-header">
                        <h3>Reservation</h3>
                        <p>Selectionnez votre type de billet</p>
                    </div>

                    <div class="reservation-body">

                        @forelse($event->tickets as $ticket)
                            <button class="ticket-btn"
                                    :class="selectedTicket?.id === {{ $ticket->id }} ? 'selected' : ''"
                                    @click="selectedTicket = {{ $ticket->toJson() }}">
                                <div>
                                    <div class="ticket-name">{{ $ticket->nom }}</div>
                                    <div class="ticket-dispo">
                                        {{ max(0, $ticket->quantite_totale - $ticket->quantite_vendue) }} places restantes
                                    </div>
                                </div>
                                <div style="display:flex;align-items:center;gap:10px">
                                    <span class="ticket-price">{{ number_format($ticket->prix, 0, ',', ' ') }} XOF</span>
                                    <div class="ticket-check">
                                        <svg width="10" height="10" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </button>
                        @empty
                            <div style="text-align:center;padding:20px 0;color:#aaa;font-size:14px">
                                Entree libre - aucun billet requis
                            </div>
                        @endforelse

                        @if($event->tickets->count() > 0)
                            <div class="reservation-total">
                                <span class="reservation-total-label">Total</span>
                                <span class="reservation-total-amount"
                                      x-text="total > 0
                                          ? total.toLocaleString('fr-FR') + ' XOF'
                                          : '-'">
                                    -
                                </span>
                            </div>
                        @endif

                        @auth
                            @if($event->nb_places > 0)
                                @if(($event->est_gratuit || $event->prix == 0) && $event->tickets->count() === 0)
                                    {{-- Evenement gratuit SANS tickets - Bouton Participer --}}
                                    <a href="{{ route('booking.confirm.show', $event->slug) }}" class="btn-acheter active">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0
                                                     110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0
                                                     110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                        Participer
                                    </a>
                                @else
                                    {{-- Evenement payable - Bouton Acheter maintenant --}}
                                    <a href="#"
                                       @click.prevent="if(selectedTicket || {{ $event->tickets->count() }} === 0) { const ticketId = selectedTicket?.id || ''; const url = '{{ route('payment.show', $event->slug) }}' + (ticketId ? '?ticket_id=' + ticketId : ''); window.location.href = url; }"
                                       class="btn-acheter"
                                       :class="(selectedTicket || {{ $event->tickets->count() }} === 0)
                                           ? 'active' : 'disabled'"
                                       :style="(!selectedTicket && {{ $event->tickets->count() }} > 0) ? 'pointer-events:none;opacity:0.6' : ''">
                                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
                                                 stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0
                                                         110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0
                                                         110-4V7a2 2 0 00-2-2H5z"/>
                                            </svg>
                                            Acheter maintenant
                                    </a>
                                @endif
                                <div class="places-restantes" style="text-align:center;margin-top:8px;font-size:13px;color:#888">
                                    @if($event->nb_places < 10)
                                        <span style="color:#CC0000">{{ $event->nb_places }} places restantes</span>
                                    @else
                                        {{ $event->nb_places }} places restantes
                                    @endif
                                </div>
                            @else
                                {{-- Evenement complet --}}
                                <div class="btn-acheter disabled" style="cursor:not-allowed">
                                    Complet
                                </div>
                                <div class="places-restantes" style="text-align:center;margin-top:8px;font-size:13px;color:#CC0000">
                                    Evenement complet
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-acheter login">
                                Se connecter pour reserver
                            </a>
                        @endauth

                        <div class="reservation-infos">
                            <div class="res-info-item">
                                <div class="res-info-icon">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
                                         stroke="#007AFF" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2
                                                 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                {{ $event->date->translatedFormat('l d F Y') }}
                            </div>
                            <div class="res-info-item">
                                <div class="res-info-icon">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
                                         stroke="#FF9500" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                {{ $event->heure }}
                            </div>
                            <div class="res-info-item">
                                <div class="res-info-icon">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
                                         stroke="#34C759" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0
                                                 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                </div>
                                {{ $event->lieu }}
                            </div>
                        </div>
                    </div>

                    <div class="reservation-guarantees">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
                             stroke="#34C759" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955
                                     11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824
                                     10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133
                                     -2.052-.382-3.016z"/>
                        </svg>
                        Paiement 100% securise via CinetPay
                    </div>
                </div>

                <div class="detail-card" style="margin-top:20px">
                    <div class="detail-card-header">
                        <div class="detail-card-icon" style="background: rgba(175, 82, 222, 0.1)">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"
                                 stroke="#AF52DE" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0
                                         00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="detail-card-title">Organisateur</h3>
                    </div>
                    <div class="detail-card-body" style="display:flex;align-items:center;gap:12px">
                        <div style="width:44px;height:44px;border-radius:50%;
                                    background:linear-gradient(135deg,#CC0000,#910000);
                                    display:flex;align-items:center;justify-content:center;
                                    color:white;font-weight:700;font-size:15px;flex-shrink:0">
                            {{ strtoupper(substr($event->user->name ?? 'E', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:14px;color:#1a1a1a">
                                {{ $event->user->name ?? 'ELEDJI' }}
                            </div>
                            <div style="font-size:12px;color:#aaa">Organisateur verifie</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        @if(isset($similarEvents) && $similarEvents->count() > 0)
            <div style="margin-top:48px;padding-top:40px;border-top:1px solid var(--gris-border)">
                <span class="section-label-sm">Vous pourriez aimer</span>
                <h2 class="section-title-sm">Evenements similaires</h2>
                <div class="similar-grid">
                    @foreach($similarEvents->take(3) as $sim)
                        <a href="{{ route('events.show', $sim) }}" class="similar-card">
                            <div class="similar-card-img">
                                <img src="{{ $sim->image_url }}" alt="{{ $sim->titre }}" loading="lazy">
                            </div>
                            <div class="similar-card-body">
                                <div class="similar-card-title">{{ $sim->titre }}</div>
                                <div class="similar-card-date">
                                    {{ $sim->date->translatedFormat('d M Y') }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
gsap.registerPlugin(ScrollTrigger);

document.querySelectorAll('[data-gsap="fade-up"]').forEach((el, i) => {
    gsap.from(el, {
        scrollTrigger: { trigger: el, start: 'top 88%', toggleActions: 'play none none none' },
        y: 36, opacity: 0, duration: 0.7,
        delay: i * 0.05,
        ease: 'power2.out'
    });
});

window.addEventListener('scroll', () => {
    const hero = document.getElementById('hero-photo');
    if (hero) {
        const scroll = window.scrollY;
        hero.style.transform = `translateY(${scroll * 0.25}px) scale(1.04)`;
    }
});

const likeBtn = document.getElementById('like-btn');
const likeIcon = document.getElementById('like-icon');
let liked = false;
likeBtn?.addEventListener('click', () => {
    liked = !liked;
    likeIcon.setAttribute('fill', liked ? '#CC0000' : 'none');
    likeIcon.setAttribute('stroke', liked ? '#CC0000' : 'currentColor');
    gsap.from(likeBtn, { scale: 0.7, duration: 0.35, ease: 'elastic.out(1,0.5)' });
});

const stars = document.querySelectorAll('.star-btn');
stars.forEach((star, i) => {
    star.addEventListener('click', () => {
        stars.forEach((s, j) => {
            s.style.color = j <= i ? '#FBBF24' : '#ddd';
        });
        gsap.from(star, { scale: 0.5, duration: 0.3, ease: 'elastic.out(1,0.5)' });
    });
    star.addEventListener('mouseenter', () => {
        stars.forEach((s, j) => { s.style.color = j <= i ? '#FBBF24' : '#ddd'; });
    });
});
document.getElementById('star-rating')?.addEventListener('mouseleave', () => {
    const checked = document.querySelector('input[name="note"]:checked');
    const val = checked ? parseInt(checked.value) : 0;
    stars.forEach((s, j) => { s.style.color = j < val ? '#FBBF24' : '#ddd'; });
});

@if($event->latitude && $event->longitude)
function initDetailMap() {
    const pos = { lat: {{ $event->latitude }}, lng: {{ $event->longitude }} };
    const map = new google.maps.Map(document.getElementById('detail-map'), {
        center: pos, zoom: 15,
        styles: [
            { featureType: 'poi', stylers: [{ visibility: 'off' }] },
            { featureType: 'transit', stylers: [{ visibility: 'off' }] }
        ],
        disableDefaultUI: false,
        zoomControl: true,
        mapTypeControl: false,
        streetViewControl: false,
    });
    new google.maps.Marker({
        position: pos, map,
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 14,
            fillColor: '#CC0000',
            fillOpacity: 1,
            strokeColor: '#fff',
            strokeWeight: 3,
        }
    });

    const infowindow = new google.maps.InfoWindow({
        content: `<div style="font-family:Poppins,sans-serif;font-size:13px;font-weight:600;padding:4px 8px">
                    {{ addslashes($event->lieu) }}
                  </div>`
    });
    const marker = new google.maps.Marker({ position: pos, map });
    marker.addListener('click', () => infowindow.open(map, marker));
}
@endif
</script>
@endpush

@if($event->latitude && $event->longitude)
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initDetailMap" async defer></script>
@endif
@endsection