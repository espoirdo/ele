@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
</div>

{{-- KPI Cards --}}
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 32px;">
    <div class="card" style="text-align: center;">
        <div style="font-size: 32px; color: #C0392B; margin-bottom: 8px;">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div style="font-size: 28px; font-weight: 700; color: #1A1A1A;">{{ $stats['total_events'] }}</div>
        <div style="font-size: 13px; color: #6B7280;">Total événements</div>
    </div>

    <div class="card" style="text-align: center;">
        <div style="font-size: 32px; color: #F59E0B; margin-bottom: 8px;">
            <i class="fas fa-clock"></i>
        </div>
        <div style="font-size: 28px; font-weight: 700; color: #1A1A1A;">{{ $stats['pending_events'] }}</div>
        <div style="font-size: 13px; color: #6B7280;">En attente</div>
    </div>

    <div class="card" style="text-align: center;">
        <div style="font-size: 32px; color: #10B981; margin-bottom: 8px;">
            <i class="fas fa-users"></i>
        </div>
        <div style="font-size: 28px; font-weight: 700; color: #1A1A1A;">{{ $stats['total_users'] }}</div>
        <div style="font-size: 13px; color: #6B7280;">Utilisateurs</div>
    </div>

    <div class="card" style="text-align: center;">
        <div style="font-size: 32px; color: #3B82F6; margin-bottom: 8px;">
            <i class="fas fa-star"></i>
        </div>
        <div style="font-size: 28px; font-weight: 700; color: #1A1A1A;">{{ $stats['premium_actifs'] }}</div>
        <div style="font-size: 13px; color: #6B7280;">Premium actifs</div>
    </div>
</div>

{{-- Revenue Cards --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px;">
    <div class="card" style="text-align: center;">
        <div style="font-size: 24px; font-weight: 700; color: #1A1A1A;">
            {{ number_format($stats['total_revenus'], 0, ',', ' ') }} FCF
        </div>
        <div style="font-size: 13px; color: #6B7280;">Revenus totaux</div>
    </div>

    <div class="card" style="text-align: center;">
        <div style="font-size: 24px; font-weight: 700; color: #1A1A1A;">
            {{ number_format($stats['revenus_this_month'], 0, ',', ' ') }} FCF
        </div>
        <div style="font-size: 13px; color: #6B7280;">Ce mois</div>
    </div>

    <div class="card" style="text-align: center;">
        <div style="font-size: 24px; font-weight: 700; color: #1A1A1A;">
            {{ number_format($stats['comments_pending'], 0, ',', ' ') }}
        </div>
        <div style="font-size: 13px; color: #6B7280;">Commentaires en attente</div>
    </div>
</div>

{{-- Tables --}}
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    {{-- Recent Events --}}
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 18px; font-weight: 600;">Derniers événements</h3>
            <a href="{{ route('admin.events.index') }}" style="color: #C0392B; font-size: 13px; text-decoration: none;">Voir tout →</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Statut</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentEvents as $event)
                <tr>
                    <td>
                        <div style="font-weight: 500;">{{ $event->titre }}</div>
                        <div style="font-size: 12px; color: #6B7280;">{{ $event->user->name ?? 'N/A' }}</div>
                    </td>
                    <td>
                        @if($event->statut === 'publie')
                            <span class="badge badge-success">Publié</span>
                        @elseif($event->statut === 'en_attente')
                            <span class="badge badge-warning">En attente</span>
                        @else
                            <span class="badge badge-danger">Rejeté</span>
                        @endif
                    </td>
                    <td style="font-size: 13px;">{{ $event->date->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: #6B7280;">Aucun événement</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Recent Payments --}}
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 18px; font-weight: 600;">Derniers paiements</h3>
            <a href="{{ route('admin.payments.index') }}" style="color: #C0392B; font-size: 13px; text-decoration: none;">Voir tout →</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Montant</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPayments as $payment)
                <tr>
                    <td>
                        <div style="font-weight: 500;">{{ $payment->user->name ?? 'N/A' }}</div>
                        <div style="font-size: 12px; color: #6B7280;">{{ $payment->event->titre ?? 'N/A' }}</div>
                    </td>
                    <td style="font-weight: 600;">{{ number_format($payment->montant, 0, ',', ' ') }} FCF</td>
                    <td>
                        @if($payment->statut === 'success')
                            <span class="badge badge-success">Succès</span>
                        @elseif($payment->statut === 'pending')
                            <span class="badge badge-warning">En cours</span>
                        @else
                            <span class="badge badge-danger">Échoué</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: #6B7280;">Aucun paiement</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection