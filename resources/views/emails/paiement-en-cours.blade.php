<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement en attente</title>
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
            color: #CC0000;
            margin-bottom: 24px;
            text-align: center;
        }
        .info-box {
            background: #FFF3E0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            text-align: center;
        }
        .info-box-icon {
            width: 48px;
            height: 48px;
            background: #F57C00;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }
        .info-box-icon svg {
            width: 24px;
            height: 24px;
            stroke: white;
        }
        .info-box-title {
            font-size: 16px;
            font-weight: 700;
            color: #E65100;
            margin-bottom: 4px;
        }
        .info-box-text {
            font-size: 14px;
            color: #666;
        }
        .payment-details {
            background: #F9F9F9;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .payment-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #E0E0E0;
        }
        .payment-row:last-child {
            border-bottom: none;
        }
        .payment-label {
            font-size: 14px;
            color: #666;
        }
        .payment-value {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
        }
        .payment-method {
            display: inline-block;
            background: #CC0000;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .instructions {
            background: #F5F5F5;
            border-radius: 12px;
            padding: 20px;
            font-size: 14px;
            color: #666;
        }
        .instructions-title {
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 12px;
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
            <h1 class="email-title">Paiement en attente</h1>

            <div class="info-box">
                <div class="info-box-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="info-box-title">Finalisez votre paiement</div>
                <div class="info-box-text">Completez le paiement pour confirmer votre reservation</div>
            </div>

            <div class="payment-details">
                <div class="payment-row">
                    <span class="payment-label">Evenement</span>
                    <span class="payment-value">{{ $booking->event->titre }}</span>
                </div>
                <div class="payment-row">
                    <span class="payment-label">Montant</span>
                    <span class="payment-value">{{ number_format($booking->total, 0, ',', ' ') }} XOF</span>
                </div>
                <div class="payment-row">
                    <span class="payment-label">Methode</span>
                    <span class="payment-value">
                        <span class="payment-method">
                            @switch($methode)
                                @case('tmoney')
                                    TMoney
                                    @break
                                @case('flooz')
                                    Flooz
                                    @break
                                @case('carte')
                                    Carte bancaire
                                    @break
                            @endswitch
                        </span>
                    </span>
                </div>
                @if($telephone)
                <div class="payment-row">
                    <span class="payment-label">Numero</span>
                    <span class="payment-value">{{ $telephone }}</span>
                </div>
                @endif
            </div>

            <div class="instructions">
                <div class="instructions-title">Instructions :</div>
                <p>Suivez les instructions sur la page de paiement pour finaliser votre transaction. Une fois le paiement confirme, vous recievrez votre billet par email.</p>
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