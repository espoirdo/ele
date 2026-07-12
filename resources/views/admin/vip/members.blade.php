@extends('admin.layouts.app')

@section('title', 'Membres VIP - Admin Eledji')

@section('content')
<div class="page-content">
    <div class="page-header">
        <h1>Membres VIP</h1>
    </div>

    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total membres</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #22C55E, #16A34A);">
            <div class="stat-value">{{ $stats['vip_active'] }}</div>
            <div class="stat-label">VIP actifs</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
            <div class="stat-value">{{ $stats['vip_expired'] }}</div>
            <div class="stat-label">VIP expirés</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #6B7280, #4B5563);">
            <div class="stat-value">{{ $stats['simple'] }}</div>
            <div class="stat-label">Membres simples</div>
        </div>
    </div>

    <div class="filter-bar">
        <a href="{{ route('admin.vip.members') }}" class="filter-btn {{ !$request->status ? 'active' : '' }}">Tous</a>
        <a href="{{ route('admin.vip.members', ['status' => 'active']) }}" class="filter-btn {{ $request->status === 'active' ? 'active' : '' }}">VIP actifs</a>
        <a href="{{ route('admin.vip.members', ['status' => 'expired']) }}" class="filter-btn {{ $request->status === 'expired' ? 'active' : '' }}">VIP expirés</a>
        <a href="{{ route('admin.vip.members', ['status' => 'simple']) }}" class="filter-btn {{ $request->status === 'simple' ? 'active' : '' }}">Membres simples</a>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Statut VIP</th>
                    <th>Expiration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vipMembers as $user)
                <tr>
                    <td>
                        <div class="user-cell">
                            @if($user->avatar)
                                <img src="{{ $user->avatarUrl }}" alt="{{ $user->name }}" class="user-avatar">
                            @else
                                <div class="user-avatar-placeholder">
                                    {{ implode('', array_map(fn($n) => $n[0], explode(' ', $user->name))) }}
                                </div>
                            @endif
                            <span>{{ $user->name }}</span>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->isVip())
                            <span class="badge badge-success">Actif</span>
                        @elseif($user->is_vip && $user->vip_expires_at && $user->vip_expires_at->isPast())
                            <span class="badge badge-warning">Expiré</span>
                        @else
                            <span class="badge badge-default">Membre</span>
                        @endif
                    </td>
                    <td>
                        @if($user->vip_expires_at)
                            {{ $user->vip_expires_at->translatedFormat('d M Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div class="action-btns">
                            @if(!$user->isVip())
                                <form action="{{ route('admin.vip.activate', $user) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="action-btn activate" onclick="return confirm('Activer VIP pour ce membre?')">
                                        Activer VIP
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.vip.revoke', $user) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="action-btn revoke" onclick="return confirm('Révoquer VIP de ce membre?')">
                                        Révoquer
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px;">
                        Aucun membre trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $vipMembers->links() }}
        </div>
    </div>
</div>

<style>
.stats-grid {
    margin-bottom: 24px;
}

.stat-card {
    background: linear-gradient(135deg, var(--rouge), var(--rouge-dark));
    border-radius: 12px;
    padding: 20px;
    color: white;
}

.stat-value {
    font-size: 28px;
    font-weight: 800;
}

.stat-label {
    font-size: 13px;
    opacity: 0.85;
    margin-top: 4px;
}

.filter-bar {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
}

.filter-btn {
    padding: 8px 16px;
    border-radius: 20px;
    background: #f1f5f9;
    color: #475569;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.filter-btn:hover {
    background: #e2e8f0;
}

.filter-btn.active {
    background: var(--rouge);
    color: white;
}

.user-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar, .user-avatar-placeholder {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
}

.user-avatar-placeholder {
    background: var(--rouge);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
}

.badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success {
    background: #dcfce7;
    color: #16a34a;
}

.badge-warning {
    background: #fef3c7;
    color: #d97706;
}

.badge-default {
    background: #f1f5f9;
    color: #475569;
}

.action-btns {
    display: flex;
    gap: 8px;
}

.action-btn {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.action-btn.activate {
    background: #22c55e;
    color: white;
}

.action-btn.activate:hover {
    background: #16a34a;
}

.action-btn.revoke {
    background: #ef4444;
    color: white;
}

.action-btn.revoke:hover {
    background: #dc2626;
}
</style>
@endsection