@extends('admin.layouts.app')

@section('title', $event->titre)

@section('content')
<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 class="page-title">{{ $event->titre }}</h1>
        <div style="display: flex; gap: 12px;">
            @if($event->statut === 'brouillon')
                <form action="{{ route('admin.events.approve', $event) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success" onclick="return confirm('Approuver cet evenement ?')">
                        Approuver
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                Retour a la liste
            </a>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <div>
        {{-- Event Details --}}
        <div class="card" style="margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Informations de l'evenement</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Statut</div>
                    @switch($event->statut)
                        @case('publie')
                            <span class="badge badge-success">Publie</span>
                            @break
                        @case('brouillon')
                            <span class="badge badge-warning">Brouillon</span>
                            @break
                        @case('rejete')
                            <span class="badge badge-danger">Rejete</span>
                            @break
                    @endswitch
                </div>
                <div>
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Organisateur</div>
                    <div style="font-weight: 600;">{{ $event->user->name ?? 'N/A' }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Date</div>
                    <div style="font-weight: 600;">{{ $event->date->translatedFormat('d F Y') }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Heure</div>
                    <div style="font-weight: 600;">{{ $event->heure }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Lieu</div>
                    <div style="font-weight: 600;">{{ $event->lieu }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Categorie</div>
                    <div style="font-weight: 600;">{{ $event->category->nom ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Prix</div>
                    <div style="font-weight: 600;">
                        @if($event->est_gratuit)
                            Gratuit
                        @else
                            {{ number_format($event->prix, 0, ',', ' ') }} XOF
                        @endif
                    </div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 4px;">Cree le</div>
                    <div style="font-weight: 600;">{{ $event->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #E5E7EB;">
                <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">Description</div>
                <p style="font-size: 14px; line-height: 1.6; color: #374151;">{{ $event->description }}</p>
            </div>
        </div>

        {{-- Comments --}}
        @if($event->comments->count() > 0)
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">
                Commentaires ({{ $event->comments->count() }})
            </h3>

            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach($event->comments as $comment)
                    <div style="padding: 16px; background: #F9FAFB; border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <div style="font-weight: 600;">{{ $comment->user->name }}</div>
                            <div style="font-size: 12px; color: #6B7280;">
                                {{ $comment->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                        <div style="display: flex; gap: 4px; margin-bottom: 8px;">
                            @for($i = 1; $i <= 5; $i++)
                                <span style="color: {{ $i <= $comment->note ? '#FBBF24' : '#D1D5DB' }};">&#9733;</span>
                            @endfor
                        </div>
                        <p style="font-size: 14px; color: #374151;">{{ $comment->contenu }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div>
        {{-- Places Management --}}
        <div class="card" style="margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Gestion des places</h3>

            <div style="text-align: center; padding: 20px 0;">
                <div style="font-size: 48px; font-weight: 800; color: #CC0000;">{{ $event->nb_places }}</div>
                <div style="font-size: 14px; color: #6B7280;">places restantes</div>
            </div>

            <form action="{{ route('admin.events.updatePlaces', $event) }}" method="POST">
                @csrf
                @method('PATCH')
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6B7280; margin-bottom: 4px;">
                        Ajuster le nombre de places
                    </label>
                    <input type="number" name="nb_places" class="form-input" value="{{ $event->nb_places }}" min="0" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Mettre a jour
                </button>
            </form>

            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #E5E7EB;">
                <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">Historique</div>
                <div style="font-size: 13px; color: #374151;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span>Reservations totales:</span>
                        <span style="font-weight: 600;">{{ $event->bookings()->where('status', 'confirmee')->sum('nb_places') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Reservations en attente:</span>
                        <span style="font-weight: 600;">{{ $event->bookings()->where('status', 'en_attente')->sum('nb_places') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tickets --}}
        @if($event->tickets->count() > 0)
        <div class="card" style="margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Billets</h3>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($event->tickets as $ticket)
                    <div style="padding: 12px; background: #F9FAFB; border-radius: 8px;">
                        <div style="font-weight: 600; margin-bottom: 4px;">{{ $ticket->nom }}</div>
                        <div style="font-size: 13px; color: #6B7280;">
                            {{ number_format($ticket->prix, 0, ',', ' ') }} XOF |
                            {{ $ticket->quantite_vendue }}/{{ $ticket->quantite_totale }} vendus
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Actions --}}
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Actions</h3>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                <a href="{{ route('events.show', $event->slug) }}" target="_blank" class="btn btn-outline" style="width: 100%; text-align: center;">
                    Voir sur le site
                </a>

                <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-primary" style="width: 100%; text-align: center;">
                    Modifier
                </a>

                @if($event->statut === 'brouillon')
                    <form action="{{ route('admin.events.approve', $event) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success" style="width: 100%;" onclick="return confirm('Approuver et publier ?')">
                            Publier l'evenement
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection