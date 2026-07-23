@extends('admin.layouts.app')

@section('title', 'Paiement Premium #' . $payment->id . ' - Admin Eledji')

@section('content')
<div class="page-content">
    <div class="page-header">
        <h1>Paiement Premium #{{ $payment->id }}</h1>
        <a href="{{ route('admin.premium.payments') }}" class="btn" style="margin-left: auto;">← Retour</a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        {{-- Payment Details --}}
        <div class="card">
            <h3 style="margin-bottom: 20px; font-size: 16px; font-weight: 600;">Détails du paiement</h3>

            <table class="detail-table">
                <tr>
                    <th>ID Transaction</th>
                    <td style="font-family: monospace;">{{ $payment->transaction_id }}</td>
                </tr>
                <tr>
                    <th>Référence PZGate</th>
                    <td style="font-family: monospace;">{{ $payment->pzgate_reference ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Statut PZGate</th>
                    <td>
                        @if($payment->pzgate_status)
                            <span class="badge badge-info">{{ $payment->pzgate_status }}</span>
                        @else
                            <span style="color: #9CA3AF;">N/A</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Montant</th>
                    <td style="font-weight: 700; font-size: 18px;">{{ number_format($payment->total, 0, ',', ' ') }} XOF</td>
                </tr>
                <tr>
                    <th>Méthode de paiement</th>
                    <td>
                        <span class="methode-badge {{ $payment->moyen_paiement }}">
                            @if($payment->moyen_paiement === 'tmoney') T-Money
                            @elseif($payment->moyen_paiement === 'flooz') Flooz
                            @else Carte bancaire
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Statut</th>
                    <td>
                        @if($payment->statut === 'en_attente')
                            <span class="badge badge-warning">En attente</span>
                        @elseif($payment->statut === 'confirme')
                            <span class="badge badge-success">Confirmé</span>
                        @else
                            <span class="badge badge-danger">Annulé</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Date de création</th>
                    <td>{{ $payment->created_at->translatedFormat('d M Y à H:i') }}</td>
                </tr>
                <tr>
                    <th>Dernière mise à jour</th>
                    <td>{{ $payment->updated_at->translatedFormat('d M Y à H:i') }}</td>
                </tr>
            </table>

            {{-- Actions --}}
            @if($payment->statut === 'en_attente')
            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #E5E7EB;">
                <h4 style="margin-bottom: 12px; font-size: 14px; font-weight: 600;">Actions</h4>
                <div style="display: flex; gap: 12px;">
                    <form action="{{ route('admin.premium.payments.confirm', $payment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success" onclick="return confirm('Confirmer ce paiement et activer les options premium?')">
                            Confirmer le paiement
                        </button>
                    </form>
                    <form action="{{ route('admin.premium.payments.cancel', $payment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Annuler ce paiement?')">
                            Annuler
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        {{-- Event & User Info --}}
        <div style="display: flex; flex-direction: column; gap: 24px;">
            {{-- Event --}}
            @if($payment->event)
            <div class="card">
                <h3 style="margin-bottom: 16px; font-size: 14px; font-weight: 600; color: #6B7280;">Événement</h3>
                <a href="{{ route('admin.events.show', $payment->event) }}" style="font-weight: 600; font-size: 16px; color: var(--rouge); text-decoration: none;">
                    {{ $payment->event->titre }}
                </a>
                <div style="margin-top: 8px; font-size: 13px; color: #6B7280;">
                    {{ $payment->event->date->translatedFormat('d M Y') }}
                </div>
            </div>
            @endif

            {{-- Organisateur --}}
            @if($payment->user)
            <div class="card">
                <h3 style="margin-bottom: 16px; font-size: 14px; font-weight: 600; color: #6B7280;">Organisateur</h3>
                <div style="display: flex; align-items: center; gap: 12px;">
                    @if($payment->user->avatar)
                        <img src="{{ $payment->user->avatarUrl }}" alt="{{ $payment->user->name }}" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--rouge); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                            {{ implode('', array_map(fn($n) => $n[0], explode(' ', $payment->user->name))) }}
                        </div>
                    @endif
                    <div>
                        <a href="{{ route('admin.users.show', $payment->user) }}" style="font-weight: 600; color: #1A1A1A; text-decoration: none;">
                            {{ $payment->user->name }}
                        </a>
                        <div style="font-size: 13px; color: #6B7280;">{{ $payment->user->email }}</div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Options --}}
            @if($payment->options)
            <div class="card">
                <h3 style="margin-bottom: 16px; font-size: 14px; font-weight: 600; color: #6B7280;">Options activées</h3>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    @foreach($payment->options as $option)
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="color: #22C55E;">✓</span>
                        <span>
                            @if($option === 'mise_en_avant') Mise en avant page d'accueil
                            @elseif($option === 'newsletter') Publication newsletter
                            @elseif($option === 'reseaux_sociaux') Partage réseaux sociaux
                            @else {{ $option }}
                            @endif
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- PZGate Response --}}
            @if($payment->pzgate_response)
            <div class="card">
                <h3 style="margin-bottom: 16px; font-size: 14px; font-weight: 600; color: #6B7280;">Réponse PZGate</h3>
                <pre style="background: #1F2937; color: #E5E7EB; padding: 12px; border-radius: 6px; font-size: 11px; overflow-x: auto;">{{ json_encode($payment->pzgate_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.detail-table {
    width: 100%;
    border-collapse: collapse;
}

.detail-table th,
.detail-table td {
    padding: 12px 0;
    text-align: left;
    border-bottom: 1px solid #E5E7EB;
}

.detail-table th {
    color: #6B7280;
    font-weight: 500;
    width: 40%;
}

.methode-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.methode-badge.tmoney { background: #FEF2F2; color: var(--rouge); }
.methode-badge.flooz { background: #E8EAF6; color: #1a237e; }
.methode-badge.carte { background: #F1F5F9; color: #475569; }

.badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success { background: #DCFCE7; color: #16A34A; }
.badge-warning { background: #FEF3C7; color: #D97706; }
.badge-danger { background: #FEE2E2; color: #DC2626; }
.badge-info { background: #DBEAFE; color: #2563EB; }

.btn-success {
    background: #22C55E;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    border: none;
    font-weight: 600;
    cursor: pointer;
}

.btn-success:hover { background: #16A34A; }

.btn-danger {
    background: #DC2626;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    border: none;
    font-weight: 600;
    cursor: pointer;
}

.btn-danger:hover { background: #B91C1C; }
</style>
@endsection
