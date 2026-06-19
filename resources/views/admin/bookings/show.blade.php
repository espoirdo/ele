@extends('admin.layouts.app')

@section('title', 'Reservation #' . $booking->numero_reservation)

@section('content')
<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 class="page-title">Reservation {{ $booking->numero_reservation ?? '#' . $booking->id }}</h1>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
            Retour a la liste
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <div>
        {{-- Booking Details --}}
        <div class="card" style="margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Informations de la reservation</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Utilisateur</div>
                    <div style="font-weight: 600;">{{ $booking->user->name ?? 'N/A' }}</div>
                    <div style="font-size: 13px; color: #6B7280;">{{ $booking->user->email ?? '' }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Evenement</div>
                    <a href="{{ route('admin.events.show', $booking->event) }}" style="font-weight: 600; color: #CC0000;">
                        {{ $booking->event->titre ?? 'N/A' }}
                    </a>
                </div>
                <div>
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Nombre de places</div>
                    <div style="font-weight: 600;">{{ $booking->nb_places }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Total</div>
                    <div style="font-weight: 600;">{{ number_format($booking->total, 0, ',', ' ') }} XOF</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Statut actuel</div>
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
                </div>
                <div>
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Date de reservation</div>
                    <div style="font-weight: 600;">{{ $booking->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>

        {{-- Change Status --}}
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Modifier le statut</h3>

            <form action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST">
                @csrf
                @method('PATCH')
                <div style="display: flex; gap: 12px; align-items: flex-end;">
                    <div style="flex: 1;">
                        <select name="status" class="form-input">
                            <option value="confirmee" {{ $booking->status === 'confirmee' ? 'selected' : '' }}>Confirmee</option>
                            <option value="en_attente" {{ $booking->status === 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="annulee" {{ $booking->status === 'annulee' ? 'selected' : '' }}>Annulee</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Mettre a jour</button>
                </div>
                <p style="font-size: 12px; color: #6B7280; margin-top: 12px;">
                    @if($booking->status === 'confirmee' && $booking->event)
                        Les places ont ete decrementees de l'evenement.
                    @elseif($booking->status === 'annulee' && $booking->event)
                        Les places seront reincrementees si vous annulez.
                    @endif
                </p>
            </form>
        </div>
    </div>

    <div>
        {{-- Quick Actions --}}
        <div class="card" style="margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Actions rapides</h3>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                @if($booking->status === 'en_attente')
                    <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success" style="width: 100%;" onclick="return confirm('Confirmer le paiement ?')">
                            Marquer comme paye
                        </button>
                    </form>
                @endif

                @if($booking->status === 'confirmee')
                    <a href="{{ route('booking.success', $booking) }}" target="_blank" class="btn btn-outline" style="text-align: center;">
                        Voir le billet
                    </a>
                @endif
            </div>
        </div>

        {{-- Event Info --}}
        @if($booking->event)
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Evenement</h3>

            <div style="margin-bottom: 12px;">
                <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Places restantes</div>
                <div style="font-weight: 600; font-size: 18px;">{{ $booking->event->nb_places }}</div>
            </div>

            <a href="{{ route('admin.events.show', $booking->event) }}" class="btn btn-outline" style="width: 100%; text-align: center;">
                Voir l'evenement
            </a>
        </div>
        @endif
    </div>
</div>
@endsection