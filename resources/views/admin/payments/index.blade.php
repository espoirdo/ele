@extends('admin.layouts.app')

@section('title', 'Paiements')

@section('content')
<div class="page-header">
    <h1 class="page-title">Gestion des paiements</h1>
</div>

{{-- Stats --}}
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
    <div class="card" style="text-align: center;">
        <div style="font-size: 24px; font-weight: 700; color: #1A1A1A;">{{ number_format($stats['total'], 0, ',', ' ') }} FCF</div>
        <div style="font-size: 13px; color: #6B7280;">Total</div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 24px; font-weight: 700; color: #1A1A1A;">{{ number_format($stats['this_month'], 0, ',', ' ') }} FCF</div>
        <div style="font-size: 13px; color: #6B7280;">Ce mois</div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 24px; font-weight: 700; color: #1A1A1A;">{{ number_format($stats['tickets'], 0, ',', ' ') }} FCF</div>
        <div style="font-size: 13px; color: #6B7280;">Billeterie</div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 24px; font-weight: 700; color: #1A1A1A;">{{ number_format($stats['premium'], 0, ',', ' ') }} FCF</div>
        <div style="font-size: 13px; color: #6B7280;">Premium</div>
    </div>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom: 24px;">
    <form method="GET" style="display: flex; gap: 16px; flex-wrap: wrap;">
        <div style="width: 150px;">
            <select name="statut" class="form-input">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ request('statut') == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="success" {{ request('statut') == 'success' ? 'selected' : '' }}>Succès</option>
                <option value="failed" {{ request('statut') == 'failed' ? 'selected' : '' }}>Échoué</option>
            </select>
        </div>
        <div style="width: 150px;">
            <select name="type" class="form-input">
                <option value="">Tous types</option>
                <option value="ticket" {{ request('type') == 'ticket' ? 'selected' : '' }}>Ticket</option>
                <option value="premium" {{ request('type') == 'premium' ? 'selected' : '' }}>Premium</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>
</div>

{{-- Payments Table --}}
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID Transaction</th>
                <th>Utilisateur</th>
                <th>Événement</th>
                <th>Type</th>
                <th>Montant</th>
                <th>Statut</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td style="font-family: monospace; font-size: 12px;">{{ $payment->transaction_id }}</td>
                <td>{{ $payment->user->name ?? 'N/A' }}</td>
                <td>{{ $payment->event->titre ?? 'N/A' }}</td>
                <td>
                    @if($payment->type === 'ticket')
                        <span class="badge badge-info">Ticket</span>
                    @else
                        <span class="badge badge-warning">Premium</span>
                    @endif
                </td>
                <td style="font-weight: 600;">{{ number_format($payment->montant, 0, ',', ' ') }} FCF</td>
                <td>
                    @switch($payment->statut)
                        @case('success')
                            <span class="badge badge-success">Succès</span>
                            @break
                        @case('pending')
                            <span class="badge badge-warning">En attente</span>
                            @break
                        @default
                            <span class="badge badge-danger">Échoué</span>
                    @endswitch
                </td>
                <td style="font-size: 13px;">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #6B7280; padding: 40px;">Aucun paiement</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $payments->links() }}
    </div>
</div>
@endsection