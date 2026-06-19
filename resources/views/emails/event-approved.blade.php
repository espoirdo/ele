@component('mail::message')
<div style="text-align: center; margin-bottom: 30px;">
    <div style="width: 60px; height: 60px; background: #C0392B; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-weight: 700; font-size: 16px;">
        EL<span style="font-size: 10px;">edji</span>
    </div>
</div>

<h1 style="text-align: center; font-size: 24px; font-weight: 700; color: #10B981; margin-bottom: 20px;">
    Événement approuvé!
</h1>

<p style="font-size: 16px; color: #666666; margin-bottom: 20px;">
    Excellent! Votre événement a été approuvé et est maintenant visible sur ELEDJI.
</p>

<div style="background: #F9F9F9; border-radius: 12px; padding: 20px; margin: 30px 0;">
    <h2 style="font-size: 18px; font-weight: 600; color: #1A1A1A; margin-bottom: 10px;">
        {{ $event->titre }}
    </h2>
    <p style="font-size: 14px; color: #666666;">
        📅 {{ $event->date->format('d/m/Y') }} à {{ $event->heure }}<br>
        📍 {{ $event->lieu }}
    </p>
</div>

@component('mail::button', ['url' => route('events.show', $event), 'color' => 'red'])
Voir l'événement
@endcomponent

<p style="font-size: 14px; color: #999999; margin-top: 40px; text-align: center;">
    © 2026 ELEDJI · Lomé, Togo<br>
    <a href="{{ route('home') }}" style="color: #C0392B;">eledji.com</a>
</p>
@endcomponent