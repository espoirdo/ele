@extends('admin.layouts.app')

@section('title', 'Logos et images')

@section('content')
<div class="page-header">
    <h1 class="page-title">Logos et images</h1>
</div>

<form method="POST" action="{{ route('admin.settings.logos.update') }}" enctype="multipart/form-data">
    @csrf

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
        @foreach($logos as $key => $logo)
            <div class="card logo-card">
                <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 8px; color: #1A1A1A;">
                    {{ $logo['label'] }}
                </h3>
                <p style="font-size: 13px; color: #6B7280; margin-bottom: 16px;">
                    {{ $logo['description'] }}
                </p>

                <div class="logo-preview">
                    @php
                        $imagePath = setting($key, $logo['current']);
                        $fullPath = $imagePath ? Storage::url($imagePath) : null;
                    @endphp

                    @if($fullPath && file_exists(public_path($fullPath)))
                        <img src="{{ $fullPath }}" alt="{{ $logo['label'] }}" class="preview-img">
                    @else
                        <div class="logo-placeholder">
                            <i class="fas fa-image"></i>
                            <span>Aucune image</span>
                        </div>
                    @endif
                </div>

                <div class="form-group" style="margin-top: 16px;">
                    <label class="form-label">Remplacer l'image</label>
                    <input type="file" name="{{ $key }}" class="form-input" accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp">
                    <p style="font-size: 11px; color: #9CA3AF; margin-top: 4px;">
                        Formats: PNG, JPG, JPEG, SVG, WebP. Max: 2MB
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 24px;">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Enregistrer les logos
        </button>
    </div>
</form>

@push('styles')
<style>
.logo-card {
    text-align: center;
}

.logo-preview {
    width: 100%;
    height: 150px;
    border-radius: 8px;
    overflow: hidden;
    background: #F3F4F6;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.logo-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    color: #9CA3AF;
}

.logo-placeholder i {
    font-size: 32px;
}

.logo-placeholder span {
    font-size: 12px;
}

@media (max-width: 900px) {
    .settings-tabs + div,
    div[style*="grid-template-columns"] {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 600px) {
    .settings-tabs + div,
    div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endpush
@endsection