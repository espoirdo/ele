@extends('admin.layouts.app')

@section('title', 'Reservations')

@section('content')
<div class="page-header">
    <h1 class="page-title">Gestion des reservations</h1>
</div>

{{-- Stats --}}
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
    <div class="card" style="text-align: center;">
        <div style="font-size: 24px; font-weight: 700; color: #1A1A1A;">{{ $stats['total'] }}</div>
        <div style="font-size: 13px; color: #6B7280;">Total</div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 24px; font-weight: 700; color: #2E7D32;">{{ $stats['confirmees'] }}</div>
        <div style="font-size: 13px; color: #6B7280;">Confirmees</div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 24px; font-weight: 700; color: #F57C00;">{{ $stats['en_attente'] }}</div>
        <div style="font-size: 13px; color: #6B7280;">En attente</div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 24px; font-weight: 700; color: #757575;">{{ $stats['annulees'] }}</div>
        <div style="font-size: 13px; color: #6B7280;">Annulees</div>
    </div>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom: 24px;">
    <form method="GET" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 1; min-width: 150px;">
            <label style="display: block; font-size: 12px; font-weight: 600; color: #6B7280; margin-bottom: 4px;">Rechercher</label>
            <input type="text" name="search" class="form-input" placeholder="Numero de reservation" value="{{ request('search') }}">
        </div>
        <div style="width: 150px;">
            <label style="display: block; font-size: 12px; font-weight: 600; color: #6B7280; margin-bottom: 4px;">Statut</label>
            <select name="status" class="form-input">
                <option value="">Tous les statuts</option>
                <option value="confirmee" {{ request('status') == 'confirmee' ? 'selected' : '' }}>Confirmee</option>
                <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="annulee" {{ request('status') == 'annulee' ? 'selected' : '' }}>Annulee</option>
            </select>
        </div>
        <div style="width: 180px;">
            <label style="display: block; font-size: 12px; font-weight: 600; color: #6B7280; margin-bottom: 4px;">Evenement</label>
            <select name="event_id" class="form-input">
                <option value="">Tous les evenements</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                        {{ Str::limit($event->titre, 30) }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrer</button>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Reinitialiser</a>
    </form>
</div>

{{-- Bookings Table --}}
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Numero</th>
                <th>Utilisateur</th>
                <th>Evenement</th>
                <th>Places</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr>
                <td style="font-family: monospace; font-size: 12px; font-weight: 600;">
                    {{ $booking->numero_reservation ?? '-' }}
                </td>
                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                <td>{{ $booking->event->titre ?? 'N/A' }}</td>
                <td>{{ $booking->nb_places }}</td>
                <td style="font-weight: 600;">{{ number_format($booking->total, 0, ',', ' ') }} XOF</td>
                <td>
                    @switch($booking->status)
                        @case('confirmee')
                            <span class="badge badge-success">Confirmee</span>
                            @break
                        @case('en_attente')
                            <span class="badge badge-warning">En attente</span>
                            @break
                        @case('annulee')
                            <span class="badge badge-secondary">Annulee</span>
                            @break
                    @endswitch
                </td>
                <td style="font-size: 13px;">{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline">
                            Voir
                        </a>
                        @if($booking->status === 'en_attente')
                            <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Confirmer le paiement ?')">
                                    Confirmer
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; color: #6B7280; padding: 40px;">Aucune reservation</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $bookings->links() }}
    </div>
</div>
@endsection