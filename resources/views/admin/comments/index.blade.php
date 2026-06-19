@extends('admin.layouts.app')

@section('title', 'Commentaires')

@section('content')
<div class="page-header">
    <h1 class="page-title">Gestion des commentaires</h1>
</div>

{{-- Tabs --}}
<div style="display: flex; gap: 16px; margin-bottom: 24px;">
    <a href="{{ route('admin.comments.index') }}"
       class="btn {{ !request('tab') ? 'btn-primary' : 'btn-outline' }}">
        Tous
    </a>
    <a href="{{ route('admin.comments.index', ['tab' => 'pending']) }}"
       class="btn {{ request('tab') == 'pending' ? 'btn-primary' : 'btn-outline' }}">
        En attente
    </a>
    <a href="{{ route('admin.comments.index', ['tab' => 'signaled']) }}"
       class="btn {{ request('tab') == 'signaled' ? 'btn-primary' : 'btn-outline' }}">
        Signalés
    </a>
</div>

{{-- Comments --}}
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Événement</th>
                <th>Commentaire</th>
                <th>Note</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($comments as $comment)
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #E5E7EB; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600;">
                            {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                        </div>
                        {{ $comment->user->name }}
                    </div>
                </td>
                <td style="max-width: 150px;">
                    <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $comment->event->titre }}
                    </div>
                </td>
                <td style="max-width: 250px;">
                    <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 13px;">
                        {{ $comment->contenu }}
                    </div>
                </td>
                <td>
                    <span style="color: #F59E0B;">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $comment->note ? '★' : '☆' }}
                        @endfor
                    </span>
                </td>
                <td>
                    @if($comment->signale)
                        <span class="badge badge-danger">Signalé</span>
                    @elseif(!$comment->approuve)
                        <span class="badge badge-warning">En attente</span>
                    @else
                        <span class="badge badge-success">Approuvé</span>
                    @endif
                </td>
                <td style="font-size: 13px;">{{ $comment->created_at->format('d/m/Y') }}</td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        @if(!$comment->approuve)
                            <form method="POST" action="{{ route('admin.comments.approve', $comment) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success" style="padding: 6px 12px; font-size: 12px;" title="Approuver">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Supprimer ce commentaire?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #6B7280; padding: 40px;">Aucun commentaire</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $comments->links() }}
    </div>
</div>
@endsection