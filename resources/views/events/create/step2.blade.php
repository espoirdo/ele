@extends('layouts.app')

@section('title', 'Creer un evenement - Etape 2 sur 4 - ELEDJI')

@section('content')
<div class="create-event-page">
    <div class="create-event-container">
        {{-- Progress Bar --}}
        @include('events.create.progress-bar', ['currentStep' => 2])

        {{-- Header --}}
        <div class="create-event-header">
            <h1>Creer un evenement - Etape 2 sur 4</h1>
            <p>Lieu, date et heure</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('events.create.step2.post') }}" method="POST" class="create-event-form">
            @csrf

            <div class="form-card">
                <div class="form-group">
                    <label for="lieu">Lieu</label>
                    <input type="text"
                           id="lieu"
                           name="lieu"
                           value="{{ old('lieu', $data['lieu'] ?? '') }}"
                           required
                           placeholder="Adresse ou lieu de l'evenement">
                    <input type="hidden" name="latitude" value="{{ old('latitude', $data['latitude'] ?? '') }}">
                    <input type="hidden" name="longitude" value="{{ old('longitude', $data['longitude'] ?? '') }}">
                    @error('lieu')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date"
                           id="date"
                           name="date"
                           value="{{ old('date', $data['date'] ?? '') }}"
                           required
                           min="{{ date('Y-m-d') }}">
                    @error('date')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="heure_debut">Heure de debut</label>
                        <input type="time"
                               id="heure_debut"
                               name="heure_debut"
                               value="{{ old('heure_debut', $data['heure_debut'] ?? '') }}"
                               required>
                        @error('heure_debut')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="heure_fin">Heure de fin</label>
                        <input type="time"
                               id="heure_fin"
                               name="heure_fin"
                               value="{{ old('heure_fin', $data['heure_fin'] ?? '') }}"
                               required>
                        @error('heure_fin')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <div class="form-navigation">
                <a href="{{ route('events.create.step1') }}" class="btn btn-secondary btn-prev">
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
    max-width: 720px;
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

.form-group {
    margin-bottom: 24px;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 500;
    color: #444444;
    margin-bottom: 6px;
}

.form-group input,
.form-group select,
.form-group textarea {
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

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #CC0000;
    box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.08);
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: #999;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.error-message {
    display: block;
    font-size: 12px;
    color: #CC0000;
    margin-top: 6px;
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

.btn-prev {
    min-width: 140px;
}

.btn-next {
    min-width: 160px;
}

@media (max-width: 600px) {
    .form-card {
        padding: 24px 20px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .create-event-header h1 {
        font-size: 22px;
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