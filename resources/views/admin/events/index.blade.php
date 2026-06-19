@extends('admin.layouts.app')

@section('title', 'Événements')

@section('content')
<div class="page-header">
    <h1 class="page-title">Gestion des événements</h1>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom: 24px;">
    <form method="GET" style="display: flex; gap: 16px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <input type="text" name="search" class="form-input" placeholder="Rechercher..." value="{{ request('search') }}">
        </div>
        <div style="width: 150px;">
            <select name="statut" class="form-input">
                <option value="">Tous les statuts</option>
                <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="publie" {{ request('statut') == 'publie' ? 'selected' : '' }}>Publié</option>
                <option value="rejete" {{ request('statut') == 'rejete' ? 'selected' : '' }}>Rejeté</option>
            </select>
        </div>
        <div style="width: 150px;">
            <select name="categorie" class="form-input">
                <option value="">Toutes catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>
</div>

{{-- Events Table --}}
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Titre</th>
                <th>Organisateur</th>
                <th>Catégorie</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
            <tr>
                <td>
                    <img src="{{ $event->image_url }}" alt="" style="width: 60px; height: 40px; object-fit: cover; border-radius: 6px;">
                </td>
                <td>
                    <div style="font-weight: 500;">{{ $event->titre }}</div>
                    @if($event->premium_mise_en_avant)
                        <span style="background: #FEF3C7; color: #92400E; padding: 2px 8px; border-radius: 10px; font-size: 11px;">★ Premium</span>
                    @endif
                </td>
                <td>{{ $event->user->name ?? 'N/A' }}</td>
                <td>{{ $event->category->nom ?? '-' }}</td>
                <td>{{ $event->date->format('d/m/Y') }}</td>
                <td>
                    @switch($event->statut)
                        @case('publie')
                            <span class="badge badge-success">Publié</span>
                            @break
                        @case('en_attente')
                            <span class="badge badge-warning">En attente</span>
                            @break
                        @case('rejete')
                            <span class="badge badge-danger">Rejeté</span>
                            @break
                        @default
                            <span class="badge badge-info">Brouillon</span>
                    @endswitch
                </td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('events.show', $event) }}" target="_blank" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($event->statut === 'en_attente')
                            <form method="POST" action="{{ route('admin.events.approve', $event) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success" style="padding: 6px 12px; font-size: 12px;" title="Approuver">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="showRejectModal({{ $event->id }})" title="Rejeter">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Êtes-vous sûr?')" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #6B7280; padding: 40px;">Aucun événement trouvé</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $events->links() }}
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 24px; border-radius: 12px; max-width: 500px; width: 90%;">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 16px;">Rejeter l'événement</h3>
        <form method="POST" id="rejectForm">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label class="form-label">Motif du rejet</label>
                <textarea name="raison_rejet" class="form-input" rows="4" placeholder="Expliquez pourquoi cet événement est rejeté..." required></textarea>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" class="btn btn-outline" onclick="hideRejectModal()">Annuler</button>
                <button type="submit" class="btn btn-danger">Rejeter</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(eventId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = '/admin/events/' + eventId + '/reject';
    modal.style.display = 'flex';
}

function hideRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
</script>
@endsection