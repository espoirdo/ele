@extends('admin.layouts.app')

@section('title', 'Paiements VIP - Admin Eledji')

@section('content')
<div class="page-content">
    <div class="page-header">
        <h1>Paiements VIP</h1>
    </div>

    <div class="filter-bar">
        <a href="{{ route('admin.vip.payments') }}" class="filter-btn {{ !$request->statut ? 'active' : '' }}">Tous</a>
        <a href="{{ route('admin.vip.payments', ['statut' => 'en_attente']) }}" class="filter-btn {{ $request->statut === 'en_attente' ? 'active' : '' }}">En attente</a>
        <a href="{{ route('admin.vip.payments', ['statut' => 'confirme']) }}" class="filter-btn {{ $request->statut === 'confirme' ? 'active' : '' }}">Confirmés</a>
        <a href="{{ route('admin.vip.payments', ['statut' => 'echoue']) }}" class="filter-btn {{ $request->statut === 'echoue' ? 'active' : '' }}">Échoués</a>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Montant</th>
                    <th>Méthode</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>#{{ $payment->id }}</td>
                    <td>
                        <div class="user-cell">
                            @if($payment->user->avatar)
                                <img src="{{ $payment->user->avatarUrl }}" alt="{{ $payment->user->name }}" class="user-avatar">
                            @else
                                <div class="user-avatar-placeholder">
                                    {{ implode('', array_map(fn($n) => $n[0], explode(' ', $payment->user->name))) }}
                                </div>
                            @endif
                            <span>{{ $payment->user->name }}</span>
                        </div>
                    </td>
                    <td>{{ number_format($payment->montant, 0, ',', ' ') }} XOF</td>
                    <td>
                        <span class="methode-badge {{ $payment->methode }}">
                            @if($payment->methode === 'tmoney') T-Money
                            @elseif($payment->methode === 'flooz') Flooz
                            @else Carte
                            @endif
                        </span>
                    </td>
                    <td>
                        @if($payment->statut === 'en_attente')
                            <span class="badge badge-warning">En attente</span>
                        @elseif($payment->statut === 'confirme')
                            <span class="badge badge-success">Confirmé</span>
                        @else
                            <span class="badge badge-danger">Échoué</span>
                        @endif
                    </td>
                    <td>{{ $payment->created_at->translatedFormat('d M Y H:i') }}</td>
                    <td>
                        @if($payment->statut === 'en_attente')
                            <form action="{{ route('admin.vip.payments.confirm', $payment) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn confirm" onclick="return confirm('Confirmer ce paiement?')">
                                    Confirmer
                                </button>
                            </form>
                        @else
                            <span style="color: #94a3b8; font-size: 12px;">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">
                        Aucun paiement VIP trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $payments->links() }}
        </div>
    </div>
</div>

<style>
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

.methode-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.methode-badge.tmoney {
    background: #fef2f2;
    color: var(--rouge);
}

.methode-badge.flooz {
    background: #e8eaf6;
    color: #1a237e;
}

.methode-badge.carte {
    background: #f1f5f9;
    color: #475569;
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

.badge-danger {
    background: #fee2e2;
    color: #dc2626;
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

.action-btn.confirm {
    background: #22c55e;
    color: white;
}

.action-btn.confirm:hover {
    background: #16a34a;
}
</style>
@endsection