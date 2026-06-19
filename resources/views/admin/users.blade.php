@extends('layouts.app')

@section('title', 'Gestion des utilisateurs - Admin')

@section('content')
<div style="padding: 90px 24px 48px; background: #F7F5F5; min-height: calc(100vh - 120px);">
    <div style="max-width: 1100px; margin: 0 auto;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 28px;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 800; color: #1E1E1E;">Utilisateurs inscrits</h1>
                <p style="color: #666;">Liste des comptes enregistrés et accès d’administration.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" style="background: #E8192C; color: white; text-decoration: none; padding: 14px 22px; border-radius: 999px; font-weight: 700;">Tableau de bord</a>
        </div>

        @if(session('success'))
            <div style="background: #E8F7EA; color: #1E6F32; border-radius: 18px; padding: 18px; margin-bottom: 24px;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div style="background: #FDE8EA; color: #8A191F; border-radius: 18px; padding: 18px; margin-bottom: 24px;">{{ session('error') }}</div>
        @endif

        <div style="overflow-x: auto; background: white; border-radius: 24px; box-shadow: 0 24px 80px rgba(0,0,0,0.06);">
            <table style="width: 100%; border-collapse: collapse; min-width: 760px;">
                <thead style="background: #F5F5F5; color: #444;">
                    <tr>
                        <th style="padding: 18px 20px; text-align: left; font-weight: 700;">Nom</th>
                        <th style="padding: 18px 20px; text-align: left; font-weight: 700;">Email</th>
                        <th style="padding: 18px 20px; text-align: left; font-weight: 700;">Inscrit le</th>
                        <th style="padding: 18px 20px; text-align: left; font-weight: 700;">Rôle</th>
                        <th style="padding: 18px 20px; text-align: center; font-weight: 700;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr style="border-top: 1px solid #EEE;">
                            <td style="padding: 18px 20px;">{{ $user->name }}</td>
                            <td style="padding: 18px 20px;">{{ $user->email }}</td>
                            <td style="padding: 18px 20px;">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td style="padding: 18px 20px;">{{ $user->is_admin ? 'Administrateur' : 'Utilisateur' }}</td>
                            <td style="padding: 18px 20px; text-align: center;">
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: #F5F5F5; border: 1px solid #E5E5E5; color: #333; padding: 10px 18px; border-radius: 999px; cursor: pointer;">Supprimer</button>
                                    </form>
                                @else
                                    <span style="color: #999;">Votre compte</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
