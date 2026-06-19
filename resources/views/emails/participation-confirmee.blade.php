<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de participation</title>
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
            font-size: 26px;
            color: #CC0000;
            margin-bottom: 24px;
            text-align: center;
        }
        .participants-visual {
            background: linear-gradient(135deg, #FFF5F5, #FFE8E8);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
        }
        .participants-icons {
            display: flex;
            justify-content: center;
            margin-bottom: 12px;
        }
        .participant-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #CC0000, #910000);
            border: 3px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
            margin-left: -15px;
        }
        .participant-avatar:first-child {
            margin-left: 0;
        }
        .participants-text {
            font-size: 14px;
            color: #666;
            font-weight: 500;
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
            .event-summary { padding: 16px; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <div class="email-logo">Eledji</div>
        </div>

        <div class="email-body">
            <h1 class="email-title">Vous y serez !</h1>

            <div class="participants-visual">
                <div class="participants-icons">
                    <div class="participant-avatar">A</div>
                    <div class="participant-avatar">B</div>
                    <div class="participant-avatar">C</div>
                    <div class="participant-avatar">+</div>
                </div>
                <p class="participants-text">Rejoignez {{ $booking->nb_places }} participant(s) pour cet evenement</p>
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