@extends('admin.layouts.app')

@section('title', 'Paiements Premium - Admin Eledji')

@section('content')
<div class="page-content">
    <div class="page-header">
        <h1>Paiements Premium</h1>
    </div>

    {{-- Stats --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px;">
        <div class="card" style="text-align: center;">
            <div style="font-size: 24px; font-weight: 700; color: #1A1A1A;">{{ number_format($stats['total'], 0, ',', ' ') }} XOF</div>
            <div style="font-size: 13px; color: #6B7280;">Total</div>
        </div>
        <div class="card" style="text-align: center;">
            <div style="font-size: 24px; font-weight: 700; color: #1A1A1A;">{{ number_format($stats['this_month'], 0, ',', ' ') }} XOF</div>
            <div style="font-size: 13px; color: #6B7280;">Ce mois</div>
        </div>
        <div class="card" style="text-align: center;">
            <div style="font-size: 24px; font-weight: 700; color: #F59E0B;">{{ $stats['pending'] }}</div>
            <div style="font-size: 13px; color: #6B7280;">En attente</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card" style="margin-bottom: 24px;">
        <form method="GET" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: center;">
            <div style="width: 150px;">
                <select name="statut" class="form-input">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="confirme" {{ request('statut') == 'confirme' ? 'selected' : '' }}>Confirmé</option>
                    <option value="annule" {{ request('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filtrer</button>
            <a href="{{ route('admin.premium.payments') }}" class="btn" style="color: #6B7280;">Réinitialiser</a>
        </form>
    </div>

    {{-- Payments Table --}}
    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Événement</th>
                    <th>Organisateur</th>
                    <th>Options</th>
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
                        @if($payment->event)
                            <a href="{{ route('admin.events.show', $payment->event) }}" style="color: var(--rouge); font-weight: 500;">
                                {{ Str::limit($payment->event->titre, 30) }}
                            </a>
                        @else
                            <span style="color: #9CA3AF;">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($payment->user)
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
                        @else
                            <span style="color: #9CA3AF;">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($payment->options)
                            <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                @foreach($payment->options as $option)
                                    <span class="option-badge">{{ $option }}</span>
                                @endforeach
                            </div>
                        @else
                            <span style="color: #9CA3AF;">-</span>
                        @endif
                    </td>
                    <td style="font-weight: 600;">{{ number_format($payment->total, 0, ',', ' ') }} XOF</td>
                    <td>
                        <span class="methode-badge {{ $payment->moyen_paiement }}">
                            @if($payment->moyen_paiement === 'tmoney') T-Money
                            @elseif($payment->moyen_paiement === 'flooz') Flooz
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
                            <span class="badge badge-danger">Annulé</span>
                        @endif
                    </td>
                    <td style="font-size: 13px;">{{ $payment->created_at->translatedFormat('d M Y H:i') }}</td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('admin.premium.payments.show', $payment) }}" class="action-btn view">
                                Voir
                            </a>
                            @if($payment->statut === 'en_attente')
                                <form action="{{ route('admin.premium.payments.confirm', $payment) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="action-btn confirm" onclick="return confirm('Confirmer ce paiement?')">
                                        Confirmer
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px; color: #6B7280;">
                        Aucun paiement premium trouvé
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
.user-cell {
    display: flex;
    align-items: center;
    gap: 8px;
}

.user-avatar, .user-avatar-placeholder {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    object-fit: cover;
}

.user-avatar-placeholder {
    background: var(--rouge);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 700;
}

.option-badge {
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
    background: #EEF2FF;
    color: #4F46E5;
}

.methode-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.methode-badge.tmoney {
    background: #FEF2F2;
    color: var(--rouge);
}

.methode-badge.flooz {
    background: #E8EAF6;
    color: #1a237e;
}

.methode-badge.carte {
    background: #F1F5F9;
    color: #475569;
}

.badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success {
    background: #DCFCE7;
    color: #16A34A;
}

.badge-warning {
    background: #FEF3C7;
    color: #D97706;
}

.badge-danger {
    background: #FEE2E2;
    color: #DC2626;
}

.action-btn {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
}

.action-btn.view {
    background: #F1F5F9;
    color: #475569;
}

.action-btn.view:hover {
    background: #E2E8F0;
}

.action-btn.confirm {
    background: #22C55E;
    color: white;
}

.action-btn.confirm:hover {
    background: #16A34A;
}
</style>
@endsection
