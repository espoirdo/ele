@extends('admin.layouts.app')

@section('title', 'Marketplace - Admin Eledji')

@section('content')
<div class="page-content">
    <div class="page-header">
        <h1>Marketplace</h1>
        <a href="{{ route('admin.marketplace.create') }}" class="btn-primary">
            Nouveau listing
        </a>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Vendeur</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($listings as $listing)
                <tr>
                    <td>
                        @if($listing->image)
                            <img src="{{ $listing->imageUrl }}" alt="{{ $listing->titre }}" class="listing-thumb">
                        @else
                            <div class="listing-thumb-placeholder">-</div>
                        @endif
                    </td>
                    <td>
                        <div class="listing-title-cell">
                            {{ $listing->titre }}
                        </div>
                    </td>
                    <td>{{ $listing->user->name }}</td>
                    <td>{{ number_format($listing->prix, 0, ',', ' ') }} XOF</td>
                    <td>
                        @if($listing->statut === 'actif')
                            <span class="badge badge-success">Actif</span>
                        @else
                            <span class="badge badge-default">Inactif</span>
                        @endif
                    </td>
                    <td>{{ $listing->created_at->translatedFormat('d M Y') }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('admin.marketplace.edit', $listing) }}" class="action-btn edit">
                                Modifier
                            </a>
                            <form action="{{ route('admin.marketplace.destroy', $listing) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" onclick="return confirm('Supprimer ce listing?')">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">
                        Aucun listing marketplace
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $listings->links() }}
        </div>
    </div>
</div>

<style>
.listing-thumb {
    width: 50px;
    height: 40px;
    border-radius: 6px;
    object-fit: cover;
}

.listing-thumb-placeholder {
    width: 50px;
    height: 40px;
    border-radius: 6px;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 12px;
}

.listing-title-cell {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success {
    background: #dcfce7;
    color: #16a34a;
}

.badge-default {
    background: #f1f5f9;
    color: #475569;
}

.action-btns {
    display: flex;
    gap: 8px;
}

.action-btn {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.action-btn.edit {
    background: #3b82f6;
    color: white;
}

.action-btn.edit:hover {
    background: #2563eb;
}

.action-btn.delete {
    background: #ef4444;
    color: white;
}

.action-btn.delete:hover {
    background: #dc2626;
}
</style>
@endsection