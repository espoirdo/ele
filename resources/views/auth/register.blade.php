@extends('layouts.app')

@section('title', 'Inscription - ELEDJI')

@section('content')
<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>{{ setting('auth_register_title', 'Bienvenue') }}</h1>
                <p>{{ setting('auth_register_subtitle', 'Creez votre compte pour publier des evenements et gerer vos reservations.') }}</p>
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

                    {{-- Case WhatsApp --}}
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px;">
                        <input type="checkbox"
                               name="is_whatsapp"
                               id="is_whatsapp"
                               value="1"
                               {{ old('is_whatsapp') ? 'checked' : '' }}
                               style="width: 16px; height: 16px; accent-color: #25D366; cursor: pointer; flex-shrink: 0;">
                        <label for="is_whatsapp"
                               style="font-family: 'Poppins', sans-serif; font-size: 12px; color: #555555; cursor: pointer; display: flex; align-items: center; gap: 6px; margin: 0;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="#25D366">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Ce numero est egalement mon WhatsApp
                        </label>
                    </div>
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

                <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 20px;">
                    <input type="checkbox"
                           name="accept_cgu"
                           id="accept_cgu"
                           value="1"
                           {{ old('accept_cgu') ? 'checked' : '' }}
                           required
                           style="width: 18px; height: 18px; margin-top: 2px; accent-color: #CC0000; cursor: pointer; flex-shrink: 0;">
                    <label for="accept_cgu"
                           style="font-family: 'Poppins', sans-serif; font-size: 13px; color: #444444; line-height: 1.6; cursor: pointer;">
                        Je reconnais avoir lu et j'accepte les
                        <a href="{{ route('cgu') }}" target="_blank"
                           style="color: #CC0000; text-decoration: underline; font-weight: 600;">
                            Conditions Generales d'Utilisation
                        </a>,
                        la
                        <a href="{{ route('politique.confidentialite') }}" target="_blank"
                           style="color: #CC0000; text-decoration: underline; font-weight: 600;">
                            Politique de confidentialite
                        </a>
                        ainsi que la politique relative a l'utilisation des
                        <a href="{{ route('politique.cookies') }}" target="_blank"
                           style="color: #CC0000; text-decoration: underline; font-weight: 600;">
                            Cookies
                        </a>.
                    </label>
                </div>

                @error('accept_cgu')
                    <p style="color: #CC0000; font-size: 12px; margin-top: -12px; margin-bottom: 16px; font-family: 'Poppins', sans-serif;">
                        {{ $message }}
                    </p>
                @enderror

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