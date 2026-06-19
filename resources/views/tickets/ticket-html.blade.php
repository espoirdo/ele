<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - {{ $ticket_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .ticket {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        .ticket-header {
            background: linear-gradient(135deg, #CC0000, #910000);
            padding: 24px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .ticket-logo {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 2px;
        }
        .ticket-type {
            font-size: 12px;
            opacity: 0.9;
        }
        .ticket-body {
            padding: 32px;
        }
        .ticket-title {
            font-size: 26px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 24px;
            text-align: center;
        }
        .ticket-number {
            text-align: center;
            padding: 12px;
            background: #f0f0f0;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 600;
            color: #666;
        }
        .ticket-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        .detail-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #999;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .detail-value {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a1a;
        }
        .ticket-event {
            background: #f9f9f9;
            border-left: 4px solid #CC0000;
            padding: 16px;
            margin-bottom: 24px;
            border-radius: 4px;
        }
        .event-field {
            margin-bottom: 12px;
        }
        .event-field:last-child {
            margin-bottom: 0;
        }
        .event-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #999;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .event-value {
            font-size: 14px;
            color: #1a1a1a;
            font-weight: 500;
        }
        .ticket-footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #999;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-confirmee {
            background: #E8F5E9;
            color: #2E7D32;
        }
        .status-en_attente {
            background: #FFF3E0;
            color: #E65100;
        }
        @media (max-width: 600px) {
            .ticket-body {
                padding: 20px;
            }
            .ticket-title {
                font-size: 20px;
            }
            .ticket-details {
                grid-template-columns: 1fr;
                gap: 12px;
            }
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .ticket {
                box-shadow: none;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <!-- Header -->
        <div class="ticket-header">
            <div>
                <div class="ticket-logo">ELEDJI</div>
                <div class="ticket-type">Billet d'entrée</div>
            </div>
            <div class="status-badge status-{{ $status }}">
                {{ $status === 'confirmee' ? 'Confirmé' : 'En attente' }}
            </div>
        </div>

        <!-- Body -->
        <div class="ticket-body">
            <!-- Title -->
            <div class="ticket-title">{{ $event_title }}</div>

            <!-- Ticket Number -->
            <div class="ticket-number">
                <strong>N° Ticket:</strong> {{ $ticket_number }}
            </div>

            <!-- Participant Details -->
            <div class="ticket-details">
                <div class="detail-item">
                    <span class="detail-label">Participant</span>
                    <span class="detail-value">{{ $user_name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email</span>
                    <span class="detail-value" style="font-size: 13px;">{{ $user_email }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Téléphone</span>
                    <span class="detail-value">{{ $user_phone }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Places</span>
                    <span class="detail-value">{{ $nb_places }}</span>
                </div>
            </div>

            <!-- Event Information -->
            <div class="ticket-event">
                <div class="event-field">
                    <div class="event-label">📅 Date</div>
                    <div class="event-value">{{ $event_date }} à {{ $event_time }}</div>
                </div>
                <div class="event-field">
                    <div class="event-label">📍 Lieu</div>
                    <div class="event-value">{{ $event_location }}</div>
                </div>
                <div class="event-field">
                    <div class="event-label">🎫 Type de billet</div>
                    <div class="event-value">{{ $ticket_type }}</div>
                </div>
                <div class="event-field">
                    <div class="event-label">💰 Montant payé</div>
                    <div class="event-value" style="font-size: 16px; font-weight: 700; color: #CC0000;">{{ number_format($ticket_price, 0, ',', ' ') }} XOF</div>
                </div>
            </div>

            <!-- Footer -->
            <div class="ticket-footer">
                <p><strong>Réservation effectuée le:</strong> {{ $booking_date }}</p>
                <p style="margin-top: 8px;">Merci d'avoir choisi ELEDJI! Présentez ce billet à l'entrée.</p>
                <p style="margin-top: 12px; font-size: 10px; color: #ccc;">Générée le {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
