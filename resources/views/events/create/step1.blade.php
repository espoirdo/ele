@extends('layouts.app')

@section('title', 'Creer un evenement - Etape 1 sur 4 - ELEDJI')

@section('content')
<div class="create-event-page">
    <div class="create-event-container">
        {{-- Progress Bar --}}
        @include('events.create.progress-bar', ['currentStep' => 1])

        {{-- Header --}}
        <div class="create-event-header">
            <h1>Creer un evenement - Etape 1 sur 4</h1>
            <p>Informations generales</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('events.create.step1.post') }}" method="POST" class="create-event-form">
            @csrf

            <div class="form-card">
                <div class="form-group">
                    <label for="titre">Titre de l'evenement</label>
                    <input type="text"
                           id="titre"
                           name="titre"
                           value="{{ old('titre', $data['titre'] ?? '') }}"
                           required
                           maxlength="150"
                           placeholder="Nom de votre evenement">
                    @error('titre')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_id">Categorie</label>
                    <select id="category_id" name="category_id">
                        <option value="">Selectionnez une categorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $data['category_id'] ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description"
                              name="description"
                              rows="6"
                              required
                              minlength="50"
                              placeholder="Decrivez votre evenement en detail...">{{ old('description', $data['description'] ?? '') }}</textarea>
                    <small class="form-hint">Minimum 50 caracteres</small>
                    @error('description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Navigation --}}
            <div class="form-navigation">
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

.form-group textarea {
    font-family: 'Calibri', sans-serif;
    min-height: 160px;
    resize: vertical;
    line-height: 1.6;
}

.form-hint {
    display: block;
    font-size: 11px;
    color: #888;
    margin-top: 4px;
}

.error-message {
    display: block;
    font-size: 12px;
    color: #CC0000;
    margin-top: 6px;
}

.form-navigation {
    display: flex;
    justify-content: flex-end;
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
    width: 160px;
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

.btn-next {
    min-width: 160px;
}

@media (max-width: 600px) {
    .form-card {
        padding: 24px 20px;
    }

    .create-event-header h1 {
        font-size: 22px;
    }
}
</style>
@endpush
@endsection