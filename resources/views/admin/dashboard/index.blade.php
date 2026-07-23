@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
</div>

{{-- Real-time Stats Section --}}
<div style="margin-bottom: 32px;">
    <h3 style="font-size: 16px; font-weight: 600; color: #CC0000; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
        <span style="width: 8px; height: 8px; background: #CC0000; border-radius: 50%; animation: pulse 2s infinite;"></span>
        Activité en temps réel
    </h3>

    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
        {{-- Carte 1: Connectés actuellement --}}
        <div style="background: #222222; border-radius: 12px; padding: 20px 24px; border-left: 4px solid #CC0000;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="font-family: 'Poppins', sans-serif; font-size: 12px; color: #888888; font-weight: 500; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px;">
                        Connectés actuellement
                    </p>
                    <p style="font-family: 'Poppins', sans-serif; font-size: 32px; font-weight: 800; color: #FFFFFF; margin: 0;" id="connectes-maintenant">
                        {{ $realtimeStats['connectes_maintenant'] }}
                    </p>
                    <p style="font-family: 'Poppins', sans-serif; font-size: 11px; color: #666666; margin: 6px 0 0 0;">
                        Actifs dans les 15 dernières minutes
                    </p>
                </div>
                <div style="width: 40px; height: 40px; background: rgba(204,0,0,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#CC0000" stroke-width="2">
                        <circle cx="12" cy="8" r="4" fill="#CC0000"/>
                        <path d="M20 21a8 8 0 10-16 0" stroke="#CC0000" fill="none"/>
                    </svg>
                </div>
            </div>
            <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.06);">
                <span style="font-size: 11px; color: #888888; font-family: 'Poppins', sans-serif;">
                    + {{ $realtimeStats['visiteurs_aujourdhui'] }} visiteurs aujourd'hui
                </span>
            </div>
        </div>

        {{-- Carte 2: Connectés ce mois --}}
        <div style="background: #222222; border-radius: 12px; padding: 20px 24px; border-left: 4px solid #3B82F6;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="font-family: 'Poppins', sans-serif; font-size: 12px; color: #888888; font-weight: 500; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px;">
                        Connectés ce mois
                    </p>
                    <p style="font-family: 'Poppins', sans-serif; font-size: 32px; font-weight: 800; color: #FFFFFF; margin: 0;" id="connectes-ce-mois">
                        {{ $realtimeStats['connectes_ce_mois'] }}
                    </p>
                    <p style="font-family: 'Poppins', sans-serif; font-size: 11px; color: #666666; margin: 6px 0 0 0;">
                        Depuis le {{ now()->startOfMonth()->translatedFormat('d F Y') }}
                    </p>
                </div>
                <div style="width: 40px; height: 40px; background: rgba(59,130,246,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" fill="none"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Carte 3: Visiteurs du site --}}
        <div style="background: #222222; border-radius: 12px; padding: 20px 24px; border-left: 4px solid #10B981;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="font-family: 'Poppins', sans-serif; font-size: 12px; color: #888888; font-weight: 500; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px;">
                        Visiteurs du site
                    </p>
                    <p style="font-family: 'Poppins', sans-serif; font-size: 32px; font-weight: 800; color: #FFFFFF; margin: 0;" id="visiteurs-total">
                        {{ $realtimeStats['visiteurs_total'] }}
                    </p>
                    <p style="font-family: 'Poppins', sans-serif; font-size: 11px; color: #666666; margin: 6px 0 0 0;">
                        Dont {{ $realtimeStats['visiteurs_aujourdhui'] }} aujourd'hui et {{ $realtimeStats['visiteurs_ce_mois'] }} ce mois
                    </p>
                </div>
                <div style="width: 40px; height: 40px; background: rgba(16,185,129,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="2" y1="12" x2="22" y2="12"/>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Carte 4: Comptes non vérifiés --}}
        <div style="background: #222222; border-radius: 12px; padding: 20px 24px; border-left: 4px solid #F5A623;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="font-family: 'Poppins', sans-serif; font-size: 12px; color: #888888; font-weight: 500; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px;">
                        Comptes non vérifiés
                    </p>
                    <p style="font-family: 'Poppins', sans-serif; font-size: 32px; font-weight: 800; color: #FFFFFF; margin: 0;" id="non-verifies">
                        {{ $realtimeStats['non_verifies'] }}
                    </p>
                    <p style="font-family: 'Poppins', sans-serif; font-size: 11px; color: #666666; margin: 6px 0 0 0;">
                        Inscrits depuis plus de 24h sans vérification
                    </p>
                    @if($realtimeStats['non_verifies'] > 0)
                    <form action="{{ route('admin.stats.remind') }}" method="POST" style="margin-top: 8px;">
                        @csrf
                        <button type="submit" style="background: rgba(245,166,35,0.15); border: none; color: #F5A623; font-size: 11px; padding: 4px 10px; border-radius: 4px; cursor: pointer; font-family: 'Poppins', sans-serif;">
                            Envoyer un rappel
                        </button>
                    </form>
                    @endif
                </div>
                <div style="width: 40px; height: 40px; background: rgba(245,166,35,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#F5A623" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Auto-refresh indicator --}}
    <div style="text-align: center; margin-top: 12px;">
        <span style="font-size: 11px; color: #666666; font-family: 'Poppins', sans-serif;">
            Mis à jour il y a <span id="seconds-ago">0</span> secondes
        </span>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>

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
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 32px;">
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

    <div class="card" style="text-align: center; background: linear-gradient(135deg, #FFF5F5, #FFEAEA);">
        <div style="font-size: 28px; font-weight: 700; color: #CC0000;">
            {{ number_format($stats['total_badges_generated'], 0, ',', ' ') }}
        </div>
        <div style="font-size: 13px; color: #6B7280;">Badges générés</div>
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

{{-- Auto-refresh JavaScript --}}
<script>
let secondsCounter = 0;

function updateStats() {
    fetch('{{ route("admin.stats.live") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('connectes-maintenant').textContent = data.connectes_actuellement;
            document.getElementById('connectes-ce-mois').textContent = data.connectes_ce_mois;
            document.getElementById('visiteurs-total').textContent = data.visiteurs_total;
            document.getElementById('non-verifies').textContent = data.non_verifies;
            secondsCounter = 0;
        })
        .catch(error => console.error('Error updating stats:', error));
}

setInterval(() => {
    secondsCounter++;
    document.getElementById('seconds-ago').textContent = secondsCounter;

    if (secondsCounter >= 60) {
        updateStats();
    }
}, 1000);
</script>
@endsection