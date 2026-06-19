@component('mail::message')
<div style="text-align: center; margin-bottom: 30px;">
    <div style="width: 60px; height: 60px; background: #C0392B; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-weight: 700; font-size: 16px;">
        EL<span style="font-size: 10px;">edji</span>
    </div>
</div>

<h1 style="text-align: center; font-size: 24px; font-weight: 700; color: #1A1A1A; margin-bottom: 20px;">
    Bienvenue sur ELEDJI!
</h1>

<p style="font-size: 16px; color: #666666; margin-bottom: 30px;">
    Bonjour <strong>{{ $name }}</strong>,
</p>

<p style="font-size: 15px; color: #666666; line-height: 1.6; margin-bottom: 30px;">
    Nous sommes ravis de vous accueillir sur ELEDJI, la plateforme d'événements numéro 1 à Lomé. Vous pouvez maintenant:
</p>

<ul style="font-size: 15px; color: #666666; line-height: 1.8; margin-bottom: 30px; padding-left: 20px;">
    <li>Découvrir des événements passionnants</li>
    <li>Créer et organiser vos propres événements</li>
    <li>Réserver vos places en quelques clics</li>
    <li>Partager vos expériences</li>
</ul>

@component('mail::button', ['url' => route('home'), 'color' => 'red'])
Découvrir les événements
@endcomponent

<p style="font-size: 14px; color: #999999; margin-top: 40px; text-align: center;">
    © 2026 ELEDJI · Lomé, Togo<br>
    <a href="{{ route('home') }}" style="color: #C0392B;">eledji.com</a>
</p>
@endcomponent