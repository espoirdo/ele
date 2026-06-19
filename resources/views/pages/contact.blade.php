@extends('layouts.app')

@section('title', 'Contact - ELEDJI')

@section('content')
<div class="contact-page">
    <div class="contact-container">
        <div class="contact-header">
            <h1>CONTACTEZ-NOUS</h1>
            <p>Nous sommes la pour repondre a vos questions et remarques</p>
        </div>

        <div class="contact-grid">
            {{-- Formulaire de contact --}}
            <div class="contact-card">
                <h2>Envoyez-nous un message</h2>
                <form class="contact-form">
                    <div class="form-group">
                        <label for="name">Nom complet</label>
                        <input type="text" id="name" name="name" required placeholder="Votre nom">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required placeholder="votre@email.com">
                    </div>

                    <div class="form-group">
                        <label for="subject">Sujet</label>
                        <input type="text" id="subject" name="subject" required placeholder="Le sujet de votre message">
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5" required placeholder="Votre message..."></textarea>
                    </div>

                    <button type="submit" class="btn-primary btn-full">
                        Envoyer le message
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Informations de contact --}}
            <div class="contact-info">
                <h2>Nos coordonnees</h2>

                <div class="contact-items">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="contact-details">
                            <h3>Email</h3>
                            <p>contact@eledji.com</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div class="contact-details">
                            <h3>Telephone</h3>
                            <p>+228 XX XX XX XX</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="contact-details">
                            <h3>Adresse</h3>
                            <p>Lome, Togo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.contact-page {
    min-height: calc(100vh - 200px);
    padding: 120px 24px 60px;
    background: #F9F9F9;
}

.contact-container {
    max-width: 1000px;
    margin: 0 auto;
}

.contact-header {
    text-align: center;
    margin-bottom: 48px;
}

.contact-header h1 {
    font-size: 32px;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.contact-header p {
    color: #666;
    font-size: 14px;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 40px;
    align-items: start;
}

@media (max-width: 900px) {
    .contact-grid {
        grid-template-columns: 1fr;
    }
}

.contact-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    padding: 36px;
}

.contact-card h2 {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 28px;
}

.contact-form {
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

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid #E4E4E4;
    border-radius: 12px;
    font-size: 13px;
    font-family: 'Poppins', sans-serif;
    transition: all 0.25s ease;
    background: white;
    color: #1a1a1a;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #CC0000;
    box-shadow: 0 0 0 3px rgba(204, 0, 0, 0.1);
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: #999;
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
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
    gap: 10px;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(204, 0, 0, 0.35);
}

.btn-full {
    width: 100%;
    margin-top: 8px;
}

.contact-info {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    padding: 36px;
}

.contact-info h2 {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 28px;
}

.contact-items {
    display: flex;
    flex-direction: column;
    gap: 28px;
}

.contact-item {
    display: flex;
    gap: 16px;
}

.contact-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(204, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.contact-icon svg {
    color: #CC0000;
}

.contact-details h3 {
    font-size: 14px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 4px;
}

.contact-details p {
    font-size: 13px;
    color: #666;
    margin: 0;
}

@media (max-width: 600px) {
    .contact-header h1 {
        font-size: 26px;
    }

    .contact-card,
    .contact-info {
        padding: 28px 24px;
    }
}
</style>
@endpush
@endsection