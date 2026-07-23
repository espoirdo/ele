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
        {{-- Ticket Types Management --}}
        <div class="card" style="margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Types de billets</h3>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                @forelse($event->tickets_actifs as $ticket)
                    <div style="padding: 12px; background: #F9FAFB; border-radius: 8px; border-left: 4px solid {{ $ticket['couleur'] }};">
                        <div style="font-weight: 600; margin-bottom: 4px; color: {{ $ticket['couleur'] }};">{{ $ticket['nom'] }}</div>
                        <div style="font-size: 13px; color: #6B7280;">
                            {{ $ticket['est_gratuit'] ? 'Gratuit' : number_format($ticket['prix'], 0, ',', ' ') . ' XOF' }}
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 20px; color: #6B7280;">
                        Aucun type de billet actif
                    </div>
                @endforelse
            </div>

            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #E5E7EB;">
                <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">Statistiques</div>
                <div style="font-size: 13px; color: #374151;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span>Reservations confirmees:</span>
                        <span style="font-weight: 600;">{{ $event->bookings()->where('status', 'confirmee')->count() }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Reservations en attente:</span>
                        <span style="font-weight: 600;">{{ $event->bookings()->where('status', 'en_attente')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tickets (legacy) --}}
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

        {{-- Badge J'y serai Section --}}
        <div class="card" style="margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Badge "J'y serai"</h3>

            @if($event->affiche_officielle)
                <div style="margin-bottom: 20px;">
                    <div style="position: relative; display: inline-block;">
                        <img src="{{ $event->afficheOfficielleUrl }}" alt="Affiche officielle" style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        @if($event->badge_zone_x)
                            <svg style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;"
                                 viewBox="0 0 100 100" preserveAspectRatio="none">
                                @if($event->badge_zone_type === 'cercle')
                                    <circle cx="{{ $event->badge_zone_x }}" cy="{{ $event->badge_zone_y }}" r="{{ $event->badge_zone_width / 2 }}"
                                            fill="rgba(204, 0, 0, 0.35)" stroke="#CC0000" stroke-width="1"/>
                                @else
                                    <rect x="{{ $event->badge_zone_x - $event->badge_zone_width / 2 }}"
                                          y="{{ $event->badge_zone_y - $event->badge_zone_height / 2 }}"
                                          width="{{ $event->badge_zone_width }}" height="{{ $event->badge_zone_height }}"
                                          fill="rgba(204, 0, 0, 0.35)" stroke="#CC0000" stroke-width="1"/>
                                @endif
                            </svg>
                        @endif
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">Configuration</div>
                    <div style="font-size: 13px; color: #374151;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span>Type de zone:</span>
                            <span style="font-weight: 600; text-transform: capitalize;">{{ $event->badge_zone_type ?? 'cercle' }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span>Position:</span>
                            <span style="font-weight: 600;">X: {{ $event->badge_zone_x ?? 50 }}%, Y: {{ $event->badge_zone_y ?? 50 }}%</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Dimensions:</span>
                            <span style="font-weight: 600;">{{ $event->badge_zone_width ?? 30 }}% x {{ $event->badge_zone_height ?? 30 }}%</span>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 20px; padding: 16px; background: #FFF5F5; border-radius: 8px; border-left: 4px solid #CC0000;">
                    <div style="font-size: 12px; color: #6B7280; margin-bottom: 8px;">Badges générés</div>
                    <div style="font-size: 36px; font-weight: 800; color: #CC0000; line-height: 1;">
                        {{ $event->badge_nb_generations ?? 0 }}
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @if(!$event->badge_valide_admin)
                        <form action="{{ route('admin.events.badge.update', $event) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="validate">
                            <button type="submit" class="btn btn-success" style="width: 100%;" onclick="return confirm('Valider la configuration du badge ?')">
                                Valider la configuration
                            </button>
                        </form>
                    @endif

                    @if($event->badge_actif)
                        <form action="{{ route('admin.events.badge.update', $event) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="disable">
                            <button type="submit" class="btn btn-outline" style="width: 100%; color: #CC0000; border-color: #CC0000;" onclick="return confirm('Désactiver le badge ?')">
                                Désactiver le badge
                            </button>
                        </form>
                    @endif
                </div>
            @else
                <div style="text-align: center; padding: 30px; color: #6B7280;">
                    <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#DDD" stroke-width="1.5" style="margin: 0 auto 12px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p>Aucune affiche officielle uploadée</p>
                    <p style="font-size: 12px;">L'organisateur n'a pas configuré le badge pour cet événement</p>
                </div>
            @endif
        </div>

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