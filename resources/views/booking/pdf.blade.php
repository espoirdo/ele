<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billet Eledji - {{ $booking->numero_reservation }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            line-height: 1.5;
        }

        .ticket-container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
        }

        .ticket-header {
            background: linear-gradient(135deg, #CC0000, #910000);
            padding: 24px 32px;
            text-align: center;
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .ticket-logo {
            font-family: 'Eras Medium ITC', serif;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .ticket-title {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            opacity: 0.9;
        }

        .ticket-divider {
            border: none;
            border-top: 2px dashed #E0E0E0;
            margin: 0;
        }

        .ticket-body {
            background: white;
            padding: 32px;
            border: 1px solid #E0E0E0;
            border-top: none;
            border-bottom: none;
        }

        .ticket-content {
            display: flex;
            gap: 24px;
        }

        .ticket-image-col {
            width: 140px;
            flex-shrink: 0;
        }

        .ticket-image {
            width: 120px;
            height: 90px;
            border-radius: 8px;
            object-fit: cover;
        }

        .ticket-image-placeholder {
            width: 120px;
            height: 90px;
            border-radius: 8px;
            background: linear-gradient(135deg, #CC0000, #910000);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ticket-image-placeholder svg {
            width: 32px;
            height: 32px;
            fill: white;
        }

        .ticket-details-col {
            flex: 1;
        }

        .ticket-event-title {
            font-family: 'Eras Medium ITC', serif;
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 12px;
        }

        .ticket-category {
            display: inline-block;
            background: #CC0000;
            color: white;
            font-size: 10px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 12px;
            margin-bottom: 16px;
            text-transform: uppercase;
        }

        .ticket-info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .ticket-info-icon {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: #F9F9F9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ticket-info-icon svg {
            width: 14px;
            height: 14px;
        }

        .ticket-info-label {
            font-size: 10px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: block;
        }

        .ticket-info-value {
            font-size: 13px;
            font-weight: 600;
            color: #1a1a1a;
            display: block;
        }

        .ticket-type-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            margin-top: 8px;
        }

        .ticket-type-badge.classique {
            background: #333333;
        }

        .ticket-type-badge.vip {
            background: #CC0000;
        }

        .ticket-type-badge.vvip {
            background: #F5A623;
        }

        .ticket-type-badge.standard {
            background: #666666;
        }

        .ticket-footer-section {
            background: #F9F9F9;
            padding: 24px 32px;
            border: 1px solid #E0E0E0;
            border-top: none;
            border-radius: 0 0 12px 12px;
            text-align: center;
        }

        .ticket-number-label {
            font-size: 10px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 6px;
        }

        .ticket-number {
            font-family: 'Poppins', monospace;
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            letter-spacing: 0.1em;
            margin-bottom: 16px;
        }

        .ticket-user-info {
            display: flex;
            justify-content: center;
            gap: 32px;
            margin-bottom: 16px;
        }

        .ticket-user-item {
            text-align: center;
        }

        .ticket-user-label {
            font-size: 10px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .ticket-user-value {
            font-size: 13px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .ticket-qr-placeholder {
            padding: 16px;
            background: white;
            border: 1px dashed #E0E0E0;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .ticket-qr-text {
            font-size: 11px;
            color: #666;
            font-style: italic;
        }

        .ticket-bottom-text {
            font-size: 11px;
            color: #999;
            font-style: italic;
            margin-bottom: 8px;
        }

        .ticket-secure-text {
            font-size: 10px;
            color: #ccc;
        }

        .ticket-copyright {
            font-size: 9px;
            color: #ddd;
            margin-top: 12px;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        {{-- Header --}}
        <div class="ticket-header">
            <div class="ticket-logo">Elōdji</div>
            <div class="ticket-title">BILLET DE PARTICIPATION</div>
        </div>

        {{-- Body --}}
        <div class="ticket-body">
            <div class="ticket-content">
                {{-- Image column --}}
                <div class="ticket-image-col">
                    @if($booking->event && $booking->event->image_couverture)
                        <img src="{{ Storage::url($booking->event->image_couverture) }}"
                             alt="{{ $booking->event->titre }}"
                             class="ticket-image">
                    @else
                        <div class="ticket-image-placeholder">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Details column --}}
                <div class="ticket-details-col">
                    <h1 class="ticket-event-title">{{ $booking->event->titre ?? 'Evenement' }}</h1>

                    @if($booking->event && $booking->event->category)
                        <span class="ticket-category">{{ $booking->event->category->nom }}</span>
                    @endif

                    <div class="ticket-info-row">
                        <div class="ticket-info-icon" style="background: rgba(0, 122, 255, 0.1);">
                            <svg fill="none" viewBox="0 0 24 24" stroke="#007AFF" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="ticket-info-label">Date</span>
                            <span class="ticket-info-value">
                                {{ $booking->event ? \Carbon\Carbon::parse($booking->event->date)->translatedFormat('l d F Y') : '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="ticket-info-row">
                        <div class="ticket-info-icon" style="background: rgba(255, 149, 0, 0.1);">
                            <svg fill="none" viewBox="0 0 24 24" stroke="#FF9500" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="ticket-info-label">Heure</span>
                            <span class="ticket-info-value">{{ $booking->event->heure ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="ticket-info-row">
                        <div class="ticket-info-icon" style="background: rgba(52, 199, 89, 0.1);">
                            <svg fill="none" viewBox="0 0 24 24" stroke="#34C759" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="ticket-info-label">Lieu</span>
                            <span class="ticket-info-value">{{ $booking->event->lieu ?? '-' }}</span>
                        </div>
                    </div>

                    @if($booking->type_billet)
                        <span class="ticket-type-badge {{ $booking->type_billet }}">
                            {{ $booking->type_billet_info['nom'] }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="ticket-footer-section">
            <div class="ticket-number-label">Numéro de réservation</div>
            <div class="ticket-number">{{ $booking->numero_reservation }}</div>

            <div class="ticket-user-info">
                <div class="ticket-user-item">
                    <div class="ticket-user-label">Participant</div>
                    <div class="ticket-user-value">{{ $booking->user->name ?? '-' }}</div>
                </div>
                <div class="ticket-user-item">
                    <div class="ticket-user-label">Email</div>
                    <div class="ticket-user-value">{{ $booking->user->email ?? '-' }}</div>
                </div>
            </div>

            <div class="ticket-qr-placeholder">
                <div class="ticket-qr-text">QR Code: {{ $booking->numero_reservation }}</div>
            </div>

            <div class="ticket-bottom-text">
                Présentez ce billet à l'entrée de l'événement
            </div>
            <div class="ticket-secure-text">
                Paiement 100% sécurisé via Eledji
            </div>
            <div class="ticket-copyright">
                © {{ date('Y') }} Eledji - Tous droits réservés
            </div>
        </div>
    </div>
</body>
</html>