@extends('layouts.app')

@section('title', 'Actualites - ELEDJI')

@section('content')
<div class="news-page">
    <div class="news-container">
        <div class="news-header">
            <h1>ACTUALITES</h1>
            <p>Les dernieres nouvelles de la plateforme Eledji</p>
        </div>

        <div class="news-card">
            <div class="news-icon">
                <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v4m6 0a2 2 0 012 2v10a2 2 0 01-2 2M9 5a2 2 0 002 2h2a2 2 0 002-2m0 0V3a2 2 0 00-2-2h-2a2 2 0 00-2 2v2z"/>
                </svg>
            </div>
            <h2>Aucune actualite pour le moment</h2>
            <p>Les actualites seront bientot publiees. Restez connecte pour les dernieres mises a jour.</p>
        </div>
    </div>
</div>

@push('styles')
<style>
.news-page {
    min-height: calc(100vh - 200px);
    padding: 120px 24px 60px;
    background: #F9F9F9;
}

.news-container {
    max-width: 700px;
    margin: 0 auto;
}

.news-header {
    text-align: center;
    margin-bottom: 48px;
}

.news-header h1 {
    font-size: 32px;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.news-header p {
    color: #666;
    font-size: 14px;
}

.news-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    padding: 60px 40px;
    text-align: center;
}

.news-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(204, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
}

.news-icon svg {
    color: #CC0000;
}

.news-card h2 {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 12px;
}

.news-card p {
    color: #666;
    font-size: 14px;
    line-height: 1.6;
    max-width: 400px;
    margin: 0 auto;
}

@media (max-width: 600px) {
    .news-header h1 {
        font-size: 26px;
    }

    .news-card {
        padding: 40px 24px;
    }
}
</style>
@endpush
@endsection