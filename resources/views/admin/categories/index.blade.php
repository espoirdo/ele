@extends('admin.layouts.app')

@section('title', 'Catégories')

@section('content')
<div class="page-header">
    <h1 class="page-title">Gestion des catégories</h1>
</div>

{{-- Add Category --}}
<div class="card" style="margin-bottom: 24px;">
    <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;">Ajouter une catégorie</h3>
    <form method="POST" action="{{ route('admin.categories.store') }}" style="display: flex; gap: 16px; flex-wrap: wrap;">
        @csrf
        <div style="flex: 1; min-width: 200px;">
            <input type="text" name="nom" class="form-input" placeholder="Nom de la catégorie" required>
        </div>
        <div style="width: 150px;">
            <input type="text" name="icone" class="form-input" placeholder="Icône (ex: fa-music)">
        </div>
        <div style="width: 100px;">
            <input type="number" name="ordre" class="form-input" placeholder="Ordre" value="0">
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

{{-- Categories Table --}}
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Ordre</th>
                <th>Nom</th>
                <th>Slug</th>
                <th>Icône</th>
                <th>Événements</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>{{ $category->ordre }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" style="display: flex; gap: 8px; align-items: center;">
                        @csrf
                        @method('PUT')
                        <input type="text" name="nom" value="{{ $category->nom }}" class="form-input" style="width: 150px; padding: 6px 10px;">
                </td>
                <td style="font-size: 13px; color: #6B7280;">{{ $category->slug }}</td>
                <td>
                    <input type="text" name="icone" value="{{ $category->icone }}" class="form-input" style="width: 120px; padding: 6px 10px;">
                </td>
                <td>{{ $category->events->count() }}</td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;">
                            <i class="fas fa-save"></i>
                        </button>
                        </form>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Supprimer cette catégorie?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #6B7280; padding: 40px;">Aucune catégorie</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection