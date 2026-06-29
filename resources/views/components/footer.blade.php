<footer class="eledji-footer">
    <div class="footer-inner">
        <div class="footer-columns">
            {{-- Colonne gauche: Logo + Liens --}}
            <div class="footer-left">
                <div class="footer-logo">
                    <div class="footer-logo-img">
                        <img src="{{ setting('logo_footer') ? Storage::url(setting('logo_footer')) : asset('images/logo.png') }}" alt="Eledji logo" loading="lazy">
                    </div>
                </div>
                <div class="footer-links">
                    <a href="{{ route('home') }}" class="footer-link">Accueil</a>
                    <a href="{{ route('events.index') }}" class="footer-link">Evenement</a>
                    <a href="#" class="footer-link">Galerie</a>
                    <a href="{{ route('contact') }}" class="footer-link">Contact</a>
                    <a href="#" class="footer-link">Confidentialite</a>
                </div>
            </div>

            {{-- Colonne droite: Newsletter --}}
            <div class="footer-right">
                <div class="newsletter-block">
                    <h3 class="newsletter-title">{{ setting('footer_newsletter_title', 'Inscrivez-vous a la newsletter') }}</h3>
                    <p class="newsletter-desc">{{ setting('footer_newsletter_text', 'Soyez informés des événements à Lomé et partout au Togo en temps réel.') }}</p>

                    {{-- Message flash --}}
                    @if(session('newsletter_success'))
                        <div class="newsletter-success">
                            {{ session('newsletter_success') }}
                        </div>
                    @endif

                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
                        @csrf
                        <div class="newsletter-pill">
                            <input type="email"
                                   name="email"
                                   class="newsletter-input"
                                   placeholder="{{ setting('footer_newsletter_placeholder', 'Votre email') }}"
                                   required>
                            <button type="submit" class="newsletter-btn">{{ setting('footer_newsletter_button', "Je m'inscris") }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Réseaux sociaux --}}
        <div class="footer-social">
            <a href="#" class="social-icon" aria-label="Facebook">
                <svg fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                </svg>
            </a>
            <a href="#" class="social-icon" aria-label="X/Twitter">
                <svg fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
            </a>
            <a href="#" class="social-icon" aria-label="Instagram">
                <svg fill="currentColor" viewBox="0 0 24 24">
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"/>
                    <circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/>
                    <circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/>
                </svg>
            </a>
            <a href="#" class="social-icon" aria-label="TikTok">
                <svg fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.79 2.79 0 012.7-2.86V8.82a8.16 8.16 0 01.68-.04A6.52 6.52 0 0119.59 6.69z"/>
                </svg>
            </a>
        </div>

        {{-- Copyright --}}
        <div class="footer-copyright">
            <p>Copyright {{ setting('footer_agency_name', 'ELEDJI') }} © 2026 {{ setting('footer_copyright', 'Tous droits reserves') }} | Prod by Ayah Communication</p>
        </div>
    </div>
</footer>

<style>
.eledji-footer {
    background: #13141C;
    padding: 64px 24px 24px;
    margin-top: 80px;
}

.footer-inner {
    max-width: 1100px;
    margin: 0 auto;
}

.footer-columns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 64px;
    margin-bottom: 48px;
}

.footer-left {
    display: flex;
    align-items: flex-start;
    gap: 32px;
}

.footer-logo {
    flex-shrink: 0;
}

.footer-logo-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px;
    border: 1.5px solid rgba(255, 255, 255, 0.25);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
}

.footer-logo-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 50%;
}

.footer-links {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding-top: 24px;
}

.footer-link {
    color: #B0B0B8;
    font-size: 16px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.25s ease;
    padding: 10px 8px;
    min-height: 44px;
    display: flex;
    align-items: center;
}

.footer-link:hover {
    color: #FFFFFF;
}

.footer-right {
    display: flex;
    align-items: flex-start;
}

.newsletter-block {
    width: 100%;
}

.newsletter-title {
    font-family: 'Eras Medium ITC', 'Eras ITC', Arial, sans-serif;
    font-size: 24px;
    font-weight: 700;
    color: #FFFFFF;
    margin: 0 0 12px 0;
    line-height: 1.3;
}

.newsletter-desc {
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    color: #B0B0B8;
    margin: 0 0 20px 0;
    line-height: 1.6;
}

.newsletter-success {
    background: rgba(52, 199, 89, 0.15);
    border: 1px solid rgba(52, 199, 89, 0.3);
    color: #34C759;
    font-size: 14px;
    font-weight: 500;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 16px;
}

.newsletter-form {
    width: 100%;
}

.newsletter-pill {
    display: flex;
    background: #FFFFFF;
    border-radius: 50px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.newsletter-input {
    flex: 1;
    border: none;
    padding: 16px 24px;
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
    color: #1a1a1a;
    background: transparent;
    outline: none;
}

.newsletter-input::placeholder {
    color: #999;
}

.newsletter-btn {
    background: linear-gradient(to right, #CC0000, #910000);
    color: #FFFFFF;
    font-size: 14px;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
    padding: 16px 28px;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.25s ease;
    white-space: nowrap;
}

.newsletter-btn:hover {
    background: linear-gradient(to right, #DD1111, #A10000);
}

.footer-social {
    display: flex;
    gap: 16px;
    justify-content: center;
    padding: 32px 0;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.social-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #23242E;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    transition: all 0.25s ease;
    text-decoration: none;
}

.social-icon:hover {
    background: #333440;
}

.social-icon svg {
    width: 18px;
    height: 18px;
}

.footer-copyright {
    background: #0D0E14;
    padding: 24px;
    text-align: center;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    margin: 0 -24px -24px -24px;
}

.footer-copyright p {
    color: #666;
    font-size: 13px;
    font-family: 'Agency FB', Arial, sans-serif;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .eledji-footer {
        padding: 48px 20px 20px;
    }

    .footer-columns {
        grid-template-columns: 1fr;
        gap: 32px;
    }

    .footer-left {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .footer-logo-img {
        width: 80px;
        height: 80px;
        padding: 10px;
    }

    .footer-links {
        align-items: center;
        padding-top: 16px;
        gap: 8px;
    }

    .footer-link {
        font-size: 14px;
        width: 100%;
        justify-content: center;
    }

    .newsletter-title {
        font-size: 20px;
        text-align: center;
    }

    .newsletter-desc {
        text-align: center;
    }

    .newsletter-pill {
        flex-direction: column;
        border-radius: 16px;
        gap: 8px;
    }

    .newsletter-input {
        padding: 14px 20px;
        text-align: center;
        min-height: 48px;
    }

    .newsletter-btn {
        padding: 14px 24px;
        border-radius: 40px;
        margin: 0 8px 8px 8px;
        min-height: 48px;
        width: 100%;
    }

    .footer-right {
        justify-content: center;
    }

    .footer-social {
        gap: 12px;
    }

    .social-icon {
        width: 48px;
        height: 48px;
    }
}
</style>