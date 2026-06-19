@component('mail::message')
<div style="text-align: center; margin-bottom: 30px;">
    <div style="width: 60px; height: 60px; background: #C0392B; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-weight: 700; font-size: 16px;">
        EL<span style="font-size: 10px;">edji</span>
    </div>
</div>

<h1 style="text-align: center; font-size: 24px; font-weight: 700; color: #EF4444; margin-bottom: 20px;">
    Événement rejeté
</h1>

<p style="font-size: 16px; color: #666666; margin-bottom: 20px;">
    Nous regrettons de vous informer que votre événement n'a pas pu être approuvé.
</p>

<div style="background: #FEF2F2; border-radius: 12px; padding: 20px; margin: 30px 0; border: 1px solid #FECACA;">
    <h2 style="font-size: 18px; font-weight: 600; color: #1A1A1A; margin-bottom: 10px;">
        {{ $event->titre }}
    </h2>
    @if($event->raison_rejet)
    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #FECACA;">
        <p style="font-size: 14px; font-weight: 600; color: #991B1B; margin-bottom: 8px;">Motif du rejet:</p>
        <p style="font-size: 14px; color: #666666;">{{ $event->raison_rejet }}</p>
    </div>
    @endif
</div>

<p style="font-size: 15px; color: #666666; margin-bottom: 30px;">
    Vous pouvez modifier votre événement et le soumettre à nouveau. N'hésitez pas à contacter notre équipe si vous avez des questions.
</p>

@component('mail::button', ['url' => route('events.create'), 'color' => 'red'])
Créer un nouvel événement
@endcomponent

<p style="font-size: 14px; color: #999999; margin-top: 40px; text-align: center;">
    © 2026 ELEDJI · Lomé, Togo<br>
    <a href="{{ route('home') }}" style="color: #C0392B;">eledji.com</a>
</p>
@endcomponent