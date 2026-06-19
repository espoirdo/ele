@component('mail::message')
<div style="text-align: center; margin-bottom: 30px;">
    <div style="width: 60px; height: 60px; background: #C0392B; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-weight: 700; font-size: 16px;">
        EL<span style="font-size: 10px;">edji</span>
    </div>
</div>

<h1 style="text-align: center; font-size: 24px; font-weight: 700; color: #10B981; margin-bottom: 20px;">
    Paiement confirmé!
</h1>

<p style="font-size: 16px; color: #666666; margin-bottom: 10px;">
    Merci pour votre achat! Votre paiement a été traité avec succès.
</p>

<div style="background: #F9F9F9; border-radius: 12px; padding: 20px; margin: 30px 0;">
    <h2 style="font-size: 18px; font-weight: 600; color: #1A1A1A; margin-bottom: 15px;">
        Reçu de paiement
    </h2>

    <table style="width: 100%; font-size: 14px;">
        <tr>
            <td style="padding: 8px 0; color: #666666;">Événement</td>
            <td style="padding: 8px 0; font-weight: 600; text-align: right;">{{ $payment->event->titre ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; color: #666666;">Type</td>
            <td style="padding: 8px 0; text-align: right;">
                {{ $payment->type === 'ticket' ? 'Billeterie' : 'Premium' }}
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 0; color: #666666;">Transaction</td>
            <td style="padding: 8px 0; text-align: right; font-family: monospace; font-size: 12px;">
                {{ $payment->transaction_id }}
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 0; color: #666666;">Date</td>
            <td style="padding: 8px 0; text-align: right;">
                {{ $payment->created_at->format('d/m/Y à H:i') }}
            </td>
        </tr>
        <tr style="border-top: 2px solid #E5E7EB;">
            <td style="padding: 12px 0 8px; font-weight: 600; color: #1A1A1A;">Montant total</td>
            <td style="padding: 12px 0 8px; font-weight: 700; font-size: 18px; text-align: right; color: #10B981;">
                {{ number_format($payment->montant, 0, ',', ' ') }} FCF
            </td>
        </tr>
    </table>
</div>

@component('mail::button', ['url' => route('events.show', $payment->event), 'color' => 'red'])
Voir l'événement
@endcomponent

<p style="font-size: 14px; color: #999999; margin-top: 40px; text-align: center;">
    © 2026 ELEDJI · Lomé, Togo<br>
    <a href="{{ route('home') }}" style="color: #C0392B;">eledji.com</a>
</p>
@endcomponent