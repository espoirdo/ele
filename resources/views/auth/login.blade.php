@extends('layouts.app')

@section('title', 'Connexion - ELEDJI')

@section('content')
<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Connexion</h1>
                <p>Connectez-vous pour creer, modifier et partager vos evenements.</p>
            </div>

            @if(session('status'))
                <div class="auth-alert auth-alert-info">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="auth-alert auth-alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="votre@email.com">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required
                        placeholder="********">
                </div>

                <div class="form-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span class="checkbox-custom"></span>
                        Se souvenir de moi
                    </label>
                    <a href="{{ route('password.request') }}" class="auth-link">Mot de passe oublie ?</a>
                </div>

                <button type="submit" class="btn-primary btn-full">Se connecter</button>
            </form>

            <p class="auth-footer">
                Pas encore de compte ? <a href="{{ route('register') }}" class="auth-link">Inscrivez-vous</a>
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
    max-width: 480px;
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

.auth-alert-info {
    background: #E8F1FF;
    color: #0B4EA1;
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
    gap: 20px;
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

.form-group input {
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

.form-group input:focus {
    border-color: #CC0000;
    box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.1);
}

.form-group input::placeholder {
    color: #999;
}

.form-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.checkbox-label {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: #555;
    font-size: 13px;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    display: none;
}

.checkbox-custom {
    width: 18px;
    height: 18px;
    border: 2px solid #CCC;
    border-radius: 4px;
    transition: all 0.25s ease;
    position: relative;
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