@extends('layouts.app')

@section('title', 'Mon profil - ELEDJI')

@push('styles')
<style>
    :root {
        --rouge: #CC0000;
        --rouge-dark: #910000;
        --gris-bg: #F9F9F9;
        --gris-border: #E0E0E0;
        --texte: #1a1a1a;
        --texte-doux: #666;
        --poppins: 'Poppins', sans-serif;
        --radius: 16px;
        --shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
    }
    *, *::before, *::after { box-sizing: border-box; }

    .profile-page {
        min-height: calc(100vh - 80px);
        padding: 60px 24px;
        background: var(--gris-bg);
        font-family: var(--poppins);
    }

    .profile-container {
        max-width: 640px;
        margin: 0 auto;
    }

    .profile-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 40px;
    }

    .profile-title {
        font-family: 'Eras Medium ITC', serif;
        font-size: 28px;
        font-weight: 700;
        color: var(--texte);
        margin: 0 0 32px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--texte);
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 14px 16px;
        border: 1.5px solid var(--gris-border);
        border-radius: 40px;
        font-size: 15px;
        font-family: var(--poppins);
        transition: border-color 0.25s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--rouge);
    }

    .btn-save {
        padding: 14px 32px;
        background: var(--rouge);
        color: white;
        border: none;
        border-radius: 40px;
        font-size: 15px;
        font-weight: 600;
        font-family: var(--poppins);
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .btn-save:hover {
        background: var(--rouge-dark);
        transform: translateY(-2px);
    }

    .btn-delete {
        padding: 14px 32px;
        background: transparent;
        color: var(--rouge);
        border: 1.5px solid var(--rouge);
        border-radius: 40px;
        font-size: 15px;
        font-weight: 600;
        font-family: var(--poppins);
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .btn-delete:hover {
        background: var(--rouge);
        color: white;
    }

    .form-actions {
        display: flex;
        gap: 16px;
        margin-top: 32px;
    }
</style>
@endpush

@section('content')
<div class="profile-page">
    <div class="profile-container">
        <div class="profile-card">
            <h1 class="profile-title">Mon profil</h1>

            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="form-group">
                    <label class="form-label" for="name">Nom</label>
                    <input class="form-input" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-input" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">Enregistrer</button>
                </div>
            </form>

            <form method="post" action="{{ route('profile.destroy') }}" style="margin-top: 32px;">
                @csrf
                @method('delete')
                <button type="submit" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte?')">
                    Supprimer mon compte
                </button>
            </form>
        </div>
    </div>
</div>
@endsection