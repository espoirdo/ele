@extends('admin.layouts.app')

@section('title', 'Utilisateurs')

@section('content')
<div class="page-header">
    <h1 class="page-title">Gestion des utilisateurs</h1>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom: 24px;">
    <form method="GET" style="display: flex; gap: 16px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <input type="text" name="search" class="form-input" placeholder="Rechercher par nom ou email..." value="{{ request('search') }}">
        </div>
        <label style="display: flex; align-items: center; gap: 8px;">
            <input type="checkbox" name="blocked" value="1" {{ request('blocked') ? 'checked' : '' }}>
            Bloqués uniquement
        </label>
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>
</div>

{{-- Users Table --}}
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Avatar</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Verified</th>
                <th>Événements</th>
                <th>Paiements</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #E5E7EB; display: flex; align-items: center; justify-content: center; font-weight: 600; color: #6B7280;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                </td>
                <td>
                    <div style="font-weight: 500;">{{ $user->name }}</div>
                    @if($user->role === 'admin')
                        <span style="background: #8B1A1A; color: white; padding: 2px 8px; border-radius: 10px; font-size: 11px;">Admin</span>
                    @endif
                </td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->email_verified_at)
                        <span class="badge badge-success">Verifie</span>
                    @else
                        <span class="badge badge-warning">Non verifie</span>
                    @endif
                </td>
                <td>{{ $user->events->count() }}</td>
                <td>{{ $user->payments->count() }}</td>
                <td>
                    @if($user->is_blocked)
                        <span class="badge badge-danger">Bloqué</span>
                    @else
                        <span class="badge badge-success">Actif</span>
                    @endif
                </td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if(!$user->email_verified_at)
                            <form method="POST" action="{{ route('admin.users.verify', $user) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success" style="padding: 6px 12px; font-size: 12px;" title="Verifier l'email">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @endif
                        @if($user->role !== 'admin')
                            @if($user->is_blocked)
                                <form method="POST" action="{{ route('admin.users.block', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success" style="padding: 6px 12px; font-size: 12px;" title="Debloquer">
                                        <i class="fas fa-unlock"></i>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.users.block', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Bloquer cet utilisateur?')" title="Bloquer">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.users.promote', $user) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Promouvoir administrateur?')" title="Promouvoir">
                                    <i class="fas fa-user-shield"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #6B7280; padding: 40px;">Aucun utilisateur trouvé</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>
@endsection