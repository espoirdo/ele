@extends('layouts.app')

@section('title', 'Inscription - ELEDJI')

@section('content')
<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Inscription</h1>
                <p>Creez votre compte pour publier des evenements et gerer vos reservations.</p>
            </div>

            @if($errors->any())
                <div class="auth-alert auth-alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                        placeholder="Votre nom complet">
                </div>

                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        placeholder="votre@email.com">
                </div>

                <div class="form-group">
                    <label for="phone">Numero de telephone</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                        placeholder="+228 00 00 00 00">
                </div>

                <div class="form-group">
                    <label for="country">Pays de provenance</label>
                    <select id="country" name="country" required>
                        <option value="" disabled {{ old('country') ? '' : 'selected' }}>Selectionnez votre pays</option>
                        <option value="TG" {{ old('country') == 'TG' ? 'selected' : '' }}>Togo</option>
                        <option value="CI" {{ old('country') == 'CI' ? 'selected' : '' }}>Cote d'Ivoire</option>
                        <option value="SN" {{ old('country') == 'SN' ? 'selected' : '' }}>Senegal</option>
                        <option value="BJ" {{ old('country') == 'BJ' ? 'selected' : '' }}>Benin</option>
                        <option value="GH" {{ old('country') == 'GH' ? 'selected' : '' }}>Ghana</option>
                        <option value="BF" {{ old('country') == 'BF' ? 'selected' : '' }}>Burkina Faso</option>
                        <option value="ML" {{ old('country') == 'ML' ? 'selected' : '' }}>Mali</option>
                        <option value="NG" {{ old('country') == 'NG' ? 'selected' : '' }}>Nigeria</option>
                        <option value="CM" {{ old('country') == 'CM' ? 'selected' : '' }}>Cameroun</option>
                        <option value="NE" {{ old('country') == 'NE' ? 'selected' : '' }}>Niger</option>
                        <option value="FR" {{ old('country') == 'FR' ? 'selected' : '' }}>France</option>
                        <option value="BE" {{ old('country') == 'BE' ? 'selected' : '' }}>Belgique</option>
                        <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                        <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>Etats-Unis</option>
                        <option value="GB" {{ old('country') == 'GB' ? 'selected' : '' }}>Royaume-Uni</option>
                        <option value="DE" {{ old('country') == 'DE' ? 'selected' : '' }}>Allemagne</option>
                        <option value="OTHER" {{ old('country') == 'OTHER' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required
                        placeholder="********">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmez le mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        placeholder="********">
                </div>

                <div class="checkbox-card">
                    <label class="checkbox-label">
                        <input type="checkbox" name="cgv" id="cgv" value="1" {{ old('cgv') ? 'checked' : '' }} required>
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">
                            J'accepte les <a href="#" class="auth-link">Conditions Generales de Vente (CGV)</a> et les conditions d'utilisation de la plateforme.
                        </span>
                    </label>
                </div>

                <div class="checkbox-card">
                    <label class="checkbox-label">
                        <input type="checkbox" name="captcha" id="captcha" value="1" {{ old('captcha') ? 'checked' : '' }} required>
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">Je ne suis pas un robot</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary btn-full">Creer mon compte</button>
            </form>

            <p class="auth-footer">
                Deja inscrit ? <a href="{{ route('login') }}" class="auth-link">Connectez-vous</a>
            </p>
        </div>
    </div>
</div>

@push('styles')
<style>
.auth-page {
    min-height: calc(100vh - 200px);
    padding: 120px 24px 60px;
    background: #F9F9F9;
    display: flex;
    align-items: center;
    justify-content: center;
}

.auth-container {
    width: 100%;
    max-width: 520px;
}

.auth-card {
    background: #FFFFFF;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    padding: 40px 36px;
}

.auth-header {
    margin-bottom: 32px;
}

.auth-header h1 {
    font-size: 28px;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.auth-header p {
    color: #666;
    font-size: 13px;
}

.auth-alert {
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 24px;
    font-size: 13px;
}

.auth-alert-error {
    background: #FDE8EA;
    color: #8A191F;
}

.auth-alert ul {
    margin: 0;
    padding-left: 18px;
}

.auth-form {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 600;
    font-size: 13px;
    color: #1a1a1a;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid #E4E4E4;
    border-radius: 12px;
    font-size: 13px;
    font-family: 'Poppins', sans-serif;
    transition: all 0.25s ease;
    background: #FFFFFF;
    color: #1a1a1a;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #CC0000;
    box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.1);
}

.form-group input::placeholder {
    color: #999;
}

.checkbox-card {
    background: #F9F9F9;
    border-radius: 12px;
    padding: 14px 16px;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    display: none;
}

.checkbox-custom {
    width: 18px;
    height: 18px;
    min-width: 18px;
    border: 2px solid #CCC;
    border-radius: 4px;
    transition: all 0.25s ease;
    position: relative;
    margin-top: 2px;
}

.checkbox-label input[type="checkbox"]:checked + .checkbox-custom {
    background: #CC0000;
    border-color: #CC0000;
}

.checkbox-label input[type="checkbox"]:checked + .checkbox-custom::after {
    content: '';
    position: absolute;
    left: 5px;
    top: 2px;
    width: 4px;
    height: 8px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.checkbox-text {
    font-size: 12px;
    color: #555;
    line-height: 1.5;
}

.btn-primary {
    background: linear-gradient(135deg, #CC0000, #910000);
    color: white;
    border: none;
    border-radius: 40px;
    padding: 14px 24px;
    font-size: 14px;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(204, 0, 0, 0.35);
}

.btn-primary:active {
    transform: translateY(0);
}

.btn-full {
    width: 100%;
    margin-top: 8px;
}

.auth-link {
    color: #CC0000;
    font-weight: 600;
    font-size: 13px;
    text-decoration: none;
    transition: all 0.25s ease;
}

.auth-link:hover {
    color: #910000;
    text-decoration: underline;
}

.auth-footer {
    margin-top: 28px;
    color: #666;
    font-size: 13px;
    text-align: center;
}

@media (max-width: 480px) {
    .auth-card {
        padding: 32px 24px;
    }

    .auth-header h1 {
        font-size: 24px;
    }
}
</style>
@endpush
@endsection