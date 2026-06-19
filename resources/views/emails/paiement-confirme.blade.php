<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement confirme</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
            background-color: #F7D6D3;
            line-height: 1.6;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
        }
        .email-header {
            background: linear-gradient(135deg, #CC0000, #910000);
            padding: 30px 40px;
            text-align: center;
        }
        .email-logo {
            color: white;
            font-family: 'Eras Medium ITC', serif;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 0.05em;
        }
        .email-body {
            background: white;
            padding: 40px;
        }
        .email-title {
            font-family: 'Eras Medium ITC', serif;
            font-size: 24px;
            color: #2E7D32;
            margin-bottom: 24px;
            text-align: center;
        }
        .success-box {
            background: #E8F5E9;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            text-align: center;
        }
        .success-box-icon {
            width: 48px;
            height: 48px;
            background: #2E7D32;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }
        .success-box-icon svg {
            width: 24px;
            height: 24px;
            stroke: white;
        }
        .success-box-title {
            font-size: 16px;
            font-weight: 700;
            color: #2E7D32;
            margin-bottom: 4px;
        }
        .success-box-text {
            font-size: 14px;
            color: #666;
        }
        .event-summary {
            background: #F9F9F9;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .event-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 16px;
            font-family: 'Eras Medium ITC', serif;
        }
        .event-details {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .event-detail {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: #666;
        }
        .event-detail-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .event-detail-icon svg {
            width: 16px;
            height: 16px;
        }
        .reservation-number {
            background: #F9F9F9;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin-bottom: 24px;
            border: 2px dashed #E0E0E0;
        }
        .reservation-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }
        .reservation-value {
            font-family: 'Poppins', monospace;
            font-size: 22px;
            font-weight: 700;
            color: #1a1a1a;
            letter-spacing: 0.1em;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        .btn-ticket {
            display: inline-block;
            background: linear-gradient(135deg, #CC0000, #910000);
            color: white;
            padding: 14px 32px;
            border-radius: 40px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .btn-ticket:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(204, 0, 0, 0.35);
        }
        .email-footer {
            background: #3D3D3D;
            padding: 30px 40px;
            text-align: center;
        }
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-bottom: 16px;
        }
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 13px;
        }
        .footer-links a:hover {
            color: white;
        }
        .footer-copyright {
            color: rgba(255, 255, 255, 0.5);
            font-size: 12px;
        }
        @media (max-width: 600px) {
            .email-body { padding: 24px; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <div class="email-logo">Eledji</div>
        </div>

        <div class="email-body">
            <h1 class="email-title">Paiement confirme !</h1>

            <div class="success-box">
                <div class="success-box-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="success-box-title">Merci pour votre paiement</div>
                <div class="success-box-text">Votre reservation est maintenant confirmee</div>
            </div>

            <div class="event-summary">
                <h2 class="event-title">{{ $booking->event->titre }}</h2>
                <div class="event-details">
                    <div class="event-detail">
                        <div class="event-detail-icon" style="background: rgba(0, 122, 255, 0.1);">
                            <svg fill="none" viewBox="0 0 24 24" stroke="#007AFF" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span>{{ $booking->event->date->translatedFormat('l d F Y') }}</span>
                    </div>
                    <div class="event-detail">
                        <div class="event-detail-icon" style="background: rgba(255, 149, 0, 0.1);">
                            <svg fill="none" viewBox="0 0 24 24" stroke="#FF9500" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span>{{ $booking->event->heure }}</span>
                    </div>
                    <div class="event-detail">
                        <div class="event-detail-icon" style="background: rgba(52, 199, 89, 0.1);">
                            <svg fill="none" viewBox="0 0 24 24" stroke="#34C759" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                        </div>
                        <span>{{ $booking->event->lieu }}</span>
                    </div>
                    <div class="event-detail">
                        <div class="event-detail-icon" style="background: rgba(204, 0, 0, 0.1);">
                            <svg fill="none" viewBox="0 0 24 24" stroke="#CC0000" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span>{{ $booking->nb_places }} place(s)</span>
                    </div>
                </div>
            </div>

            <div class="reservation-number">
                <div class="reservation-label">Numero de reservation</div>
                <div class="reservation-value">{{ $booking->numero_reservation }}</div>
            </div>

            <div class="btn-container">
                <a href="{{ route('booking.success', $booking) }}" class="btn-ticket">
                    Voir mon billet
                </a>
            </div>
        </div>

        <div class="email-footer">
            <div class="footer-links">
                <a href="{{ route('home') }}">Accueil</a>
                <a href="{{ route('events.index') }}">Evenements</a>
                <a href="{{ route('contact') }}">Contact</a>
            </div>
            <div class="footer-copyright">
                &copy; {{ date('Y') }} Eledji. Tous droits reserves.
            </div>
        </div>
    </div>
</body>
</html>