@extends('layouts.app')

@section('title', 'Tableau de bord admin')

@section('content')
<div style="padding: 90px 24px 48px; background: #F7F5F5; min-height: calc(100vh - 120px);">
    <div style="max-width: 1100px; margin: 0 auto; display: grid; gap: 32px;">
        <div style="background: white; border-radius: 28px; padding: 42px; box-shadow: 0 24px 80px rgba(0,0,0,0.08);">
            <h1 style="font-size: 2.6rem; font-weight: 800; color: #1E1E1E; margin-bottom: 10px;">Tableau de bord administrateur</h1>
            <p style="color: #666; font-size: 1rem; max-width: 760px;">Visualisez les utilisateurs inscrits et gérez les événements en attente ou publiés sur la plateforme.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 24px;">
            <div style="background: white; border-radius: 28px; padding: 32px; box-shadow: 0 24px 80px rgba(0,0,0,0.08);">
                <h2 style="font-size: 1.1rem; font-weight: 700; color: #1E1E1E; margin-bottom: 10px;">Utilisateurs</h2>
                <p style="font-size: 2.8rem; font-weight: 800; color: #E8192C; margin: 0;">{{ $usersCount ?? 0 }}</p>
                <a href="{{ route('admin.users') }}" style="margin-top: 18px; display: inline-block; color: #E8192C; font-weight: 700; text-decoration: none;">Gérer les utilisateurs →</a>
            </div>
            <div style="background: white; border-radius: 28px; padding: 32px; box-shadow: 0 24px 80px rgba(0,0,0,0.08);">
                <h2 style="font-size: 1.1rem; font-weight: 700; color: #1E1E1E; margin-bottom: 10px;">Événements</h2>
                <p style="font-size: 2.8rem; font-weight: 800; color: #E8192C; margin: 0;">{{ $eventsCount ?? 0 }}</p>
                <a href="{{ route('admin.events') }}" style="margin-top: 18px; display: inline-block; color: #E8192C; font-weight: 700; text-decoration: none;">Gérer les événements →</a>
            </div>
        </div>
    </div>
</div>
@endsection
