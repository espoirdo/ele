@extends('admin.layouts.app')

@section('title', 'Nouveau listing - Admin Eledji')

@section('content')
<div class="page-content">
    <div class="page-header">
        <h1>Nouveau listing Marketplace</h1>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.marketplace.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="user_id">Vendeur</label>
                <select name="user_id" id="user_id" required>
                    <option value="">Sélectionner un utilisateur</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" name="titre" id="titre" required placeholder="Titre du listing">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4" required placeholder="Description du produit ou service"></textarea>
            </div>

            <div class="form-group">
                <label for="prix">Prix (XOF)</label>
                <input type="number" name="prix" id="prix" required min="0" placeholder="Prix en XOF">
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image" id="image" accept="image/*">
            </div>

            <div class="form-group">
                <label for="statut">Statut</label>
                <select name="statut" id="statut" required>
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                </select>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.marketplace.index') }}" class="btn-secondary">Annuler</a>
                <button type="submit" class="btn-primary">Créer</button>
            </div>
        </form>
    </div>
</div>

<style>
.form-card {
    background: white;
    border-radius: 12px;
    padding: 32px;
    max-width: 600px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 8px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    transition: border-color 0.2s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--rouge);
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}

.btn-primary {
    padding: 12px 24px;
    background: var(--rouge);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-primary:hover {
    background: var(--rouge-dark);
}

.btn-secondary {
    padding: 12px 24px;
    background: #f1f5f9;
    color: #475569;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-secondary:hover {
    background: #e2e8f0;
}
</style>
@endsection