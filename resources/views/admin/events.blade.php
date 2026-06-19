@extends('layouts.app')

@section('title', 'Gestion des événements - Admin')

@section('content')
<div style="padding: 90px 24px 48px; background: #F7F5F5; min-height: calc(100vh - 120px);">
    <div style="max-width: 1100px; margin: 0 auto;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 28px;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 800; color: #1E1E1E;">Événements publiés</h1>
                <p style="color: #666;">Liste des événements enregistrés sur la plateforme.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" style="background: #E8192C; color: white; text-decoration: none; padding: 14px 22px; border-radius: 999px; font-weight: 700;">Tableau de bord</a>
        </div>

        @if(session('success'))
            <div style="background: #E8F7EA; color: #1E6F32; border-radius: 18px; padding: 18px; margin-bottom: 24px;">{{ session('success') }}</div>
        @endif

        <div style="overflow-x: auto; background: white; border-radius: 24px; box-shadow: 0 24px 80px rgba(0,0,0,0.06);">
            <table style="width: 100%; border-collapse: collapse; min-width: 760px;">
                <thead style="background: #F5F5F5; color: #444;">
                    <tr>
                        <th style="padding: 18px 20px; text-align: left; font-weight: 700;">Titre</th>
                        <th style="padding: 18px 20px; text-align: left; font-weight: 700;">Catégorie</th>
                        <th style="padding: 18px 20px; text-align: left; font-weight: 700;">Organisateur</th>
                        <th style="padding: 18px 20px; text-align: left; font-weight: 700;">Statut</th>
                        <th style="padding: 18px 20px; text-align: center; font-weight: 700;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr style="border-top: 1px solid #EEE;">
                            <td style="padding: 18px 20px;">{{ $event->titre }}</td>
                            <td style="padding: 18px 20px;">{{ $event->category->name ?? 'Général' }}</td>
                            <td style="padding: 18px 20px;">{{ $event->user->name ?? 'Inconnu' }}</td>
                            <td style="padding: 18px 20px;">{{ ucfirst($event->statut) }}</td>
                            <td style="padding: 18px 20px; text-align: center; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                                @if($event->statut !== 'publie')
                                    <form action="{{ route('admin.events.publish', $event) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        <button type="submit" style="background: #E8192C; color: white; border: none; padding: 10px 18px; border-radius: 999px; font-weight: 700; cursor: pointer;">Publier</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #F5F5F5; color: #333; border: 1px solid #E5E5E5; padding: 10px 18px; border-radius: 999px; cursor: pointer;">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
