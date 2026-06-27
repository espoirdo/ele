@extends('admin.layouts.app')

@section('title', 'Administrateurs')

@section('content')
<div class="page-header">
    <h1 class="page-title">Gestion des administrateurs</h1>
    <button class="btn btn-primary" onclick="document.getElementById('addAdminModal').style.display='block'">
        <i class="fas fa-plus"></i> Ajouter un administrateur
    </button>
</div>

{{-- Liste des administrateurs --}}
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Role</th>
                <th>Statut</th>
                <th>Cree le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $admin)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: #CC0000; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            {{ $admin->name }}
                            @if($admin->id === auth()->id())
                                <span class="badge badge-info">Vous</span>
                            @endif
                        </div>
                    </td>
                    <td>{{ $admin->email }}</td>
                    <td>
                        <span class="badge badge-{{ $admin->roleModel && $admin->roleModel->slug === 'admin_total' ? 'danger' : 'success' }}">
                            {{ $admin->roleModel?->nom ?? 'Administrateur' }}
                        </span>
                    </td>
                    <td>
                        @if($admin->is_blocked)
                            <span class="badge badge-danger">Desactive</span>
                        @else
                            <span class="badge badge-success">Actif</span>
                        @endif
                    </td>
                    <td>{{ $admin->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            @if($admin->id !== auth()->id())
                                {{-- Toggle statut --}}
                                <form method="POST" action="{{ route('admin.settings.admins.toggle', $admin) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $admin->is_blocked ? 'btn-success' : 'btn-outline' }}">
                                        <i class="fas fa-{{ $admin->is_blocked ? 'check' : 'ban' }}"></i>
                                        {{ $admin->is_blocked ? 'Activer' : 'Desactiver' }}
                                    </button>
                                </form>

                                {{-- Supprimer --}}
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $admin->id }}, '{{ $admin->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @else
                                <span style="font-size: 12px; color: #9CA3AF;">-</span>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Modal pour ajouter un administrateur --}}
<div id="addAdminModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Ajouter un administrateur</h3>
            <button type="button" class="close" onclick="document.getElementById('addAdminModal').style.display='none'">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.settings.admins.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Nom complet</label>
                <input type="text" name="name" class="form-input" required placeholder="John Doe">
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" required placeholder="john@example.com">
            </div>

            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role_id" class="form-input" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->nom }}</option>
                    @endforeach
                </select>
            </div>

            <p style="font-size: 13px; color: #6B7280; margin-bottom: 20px;">
                <i class="fas fa-info-circle"></i>
                Un mot de passe temporaire sera genere automatiquement et envoyé a l'administrateur.
            </p>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('addAdminModal').style.display='none'">
                    Annuler
                </button>
                <button type="submit" class="btn btn-primary">
                    Creer l'administrateur
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal pour confirmer la suppression --}}
<div id="deleteModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmer la suppression</h3>
            <button type="button" class="close" onclick="document.getElementById('deleteModal').style.display='none'">&times;</button>
        </div>

        <p>Etes-vous sur de vouloir supprimer <strong id="deleteAdminName"></strong> ?</p>
        <p style="font-size: 13px; color: #EF4444;">Cette action est irreversible.</p>

        <form method="POST" id="deleteForm" action="">
            @csrf
            @method('DELETE')

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="confirm_delete" value="1" required>
                    Je confirme la suppression
                </label>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('deleteModal').style.display='none'">
                    Annuler
                </button>
                <button type="submit" class="btn btn-danger">
                    Supprimer
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    padding: 24px;
    border-radius: 12px;
    width: 100%;
    max-width: 480px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.modal-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #1A1A1A;
}

.close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #6B7280;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.badge-success {
    background: #D1FAE5;
    color: #065F46;
}

.badge-danger {
    background: #FEE2E2;
    color: #991B1B;
}

.badge-info {
    background: #DBEAFE;
    color: #1E40AF;
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(userId, userName) {
    document.getElementById('deleteAdminName').textContent = userName;
    document.getElementById('deleteForm').action = '/admin/parametres/administrateurs/' + userId;
    document.getElementById('deleteModal').style.display = 'block';
}

// Fermer les modals en cliquant a l'exterieur
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>
@endpush
@endsection