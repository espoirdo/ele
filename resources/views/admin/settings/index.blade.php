@extends('admin.layouts.app')

@section('title', 'Paramètres')

@section('content')
<div class="page-header">
    <h1 class="page-title">Paramètres du site</h1>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        {{-- General Settings --}}
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 20px;">Général</h3>

            <div class="form-group">
                <label class="form-label">Nom du site</label>
                <input type="text" name="site_name" class="form-input" value="{{ Setting::get('site_name', 'ELEDJI') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Email de contact</label>
                <input type="email" name="contact_email" class="form-input" value="{{ Setting::get('contact_email', 'contact@eledji.com') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Téléphone de contact</label>
                <input type="text" name="contact_phone" class="form-input" value="{{ Setting::get('contact_phone', '+228 90 00 00 00') }}">
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="maintenance_mode" value="1" {{ Setting::get('maintenance_mode') ? 'checked' : '' }}>
                    Mode maintenance
                </label>
            </div>
        </div>

        {{-- Premium Prices --}}
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 20px;">Prix Premium (FCFA / jour)</h3>

            <div class="form-group">
                <label class="form-label">Mise en avant</label>
                <input type="number" name="premium_mise_en_avant_price" class="form-input" value="{{ Setting::get('premium_mise_en_avant_price', 2000) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Newsletter</label>
                <input type="number" name="premium_newsletter_price" class="form-input" value="{{ Setting::get('premium_newsletter_price', 5000) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Réseaux sociaux</label>
                <input type="number" name="premium_reseaux_price" class="form-input" value="{{ Setting::get('premium_reseaux_price', 3000) }}">
            </div>
        </div>

        {{-- API Keys --}}
        <div class="card" style="grid-column: 1 / -1;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 20px;">Clés API</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Google Maps API Key</label>
                    <input type="text" name="google_maps_key" class="form-input" value="{{ Setting::get('google_maps_key', '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">CinetPay API Key</label>
                    <input type="text" name="cinetpay_api_key" class="form-input" value="{{ Setting::get('cinetpay_api_key', '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">CinetPay Site ID</label>
                    <input type="text" name="cinetpay_site_id" class="form-input" value="{{ Setting::get('cinetpay_site_id', '') }}">
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 24px;">
        <button type="submit" class="btn btn-primary">Enregistrer les paramètres</button>
    </div>
</form>
@endsection