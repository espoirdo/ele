@extends('admin.layouts.app')

@section('title', 'Logos et images')

@section('content')
<div class="page-header">
    <h1 class="page-title">Logos et images</h1>
</div>

<form method="POST" action="{{ route('admin.settings.logos.update') }}" enctype="multipart/form-data">
    @csrf

    {{-- Section: Fond de la section hero --}}
    <div class="card" style="margin-bottom: 32px;">
        <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px; color: #1A1A1A;">
            <i class="fas fa-image" style="margin-right: 8px; color: #CC0000;"></i>
            Fond de la section hero
        </h3>
        <p style="font-size: 13px; color: #6B7280; margin-bottom: 20px;">
            Personnalisez le fond de la section hero en haut de la page d'accueil.
        </p>

        @php
            $heroType = setting('hero_background_type', 'couleur');
            $heroColor = setting('hero_background_color', '#F7D6D3');
            $heroImage = setting('hero_background_image');
        @endphp

        {{-- Type de fond (radio buttons) --}}
        <div style="display: flex; gap: 24px; margin-bottom: 24px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="radio" name="hero_background_type" value="couleur" {{ $heroType === 'couleur' ? 'checked' : '' }}
                       onchange="toggleHeroOptions()" style="accent-color: #CC0000;">
                <span style="font-weight: 500;">Couleur unie</span>
            </label>
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="radio" name="hero_background_type" value="image" {{ $heroType === 'image' ? 'checked' : '' }}
                       onchange="toggleHeroOptions()" style="accent-color: #CC0000;">
                <span style="font-weight: 500;">Image de fond</span>
            </label>
        </div>

        {{-- Option: Couleur unie --}}
        <div id="hero-color-option" style="display: {{ $heroType === 'couleur' ? 'block' : 'none' }};">
            <div style="display: flex; align-items: center; gap: 16px;">
                <label style="font-weight: 500; font-size: 14px;">Couleur de fond:</label>
                <input type="color" name="hero_background_color" value="{{ $heroColor }}"
                       style="width: 60px; height: 40px; border: none; border-radius: 8px; cursor: pointer;">
                <input type="text" name="hero_background_color_text" value="{{ $heroColor }}"
                       style="width: 100px; padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-family: monospace;"
                       onchange="document.querySelector('input[name=hero_background_color]').value = this.value">
            </div>
        </div>

        {{-- Option: Image de fond --}}
        <div id="hero-image-option" style="display: {{ $heroType === 'image' ? 'block' : 'none' }};">
            <div class="hero-bg-preview" style="width: 100%; max-width: 400px; height: 200px; border-radius: 12px; overflow: hidden; background: #f3f4f6; margin-bottom: 16px;">
                @if($heroImage && Storage::exists($heroImage))
                    <img src="{{ Storage::url($heroImage) }}" alt="Hero background" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #9ca3af;">
                        <i class="fas fa-image" style="font-size: 48px; margin-bottom: 8px;"></i>
                        <span>Aucune image</span>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label class="form-label">Télécharger une image</label>
                <input type="file" name="hero_background_image" class="form-input" accept="image/jpg,image/jpeg,image/png,image/webp">
                <p style="font-size: 11px; color: #9CA3AF; margin-top: 4px;">
                    Formats: JPG, JPEG, PNG, WebP. Max: 5MB
                </p>
            </div>
            @if($heroImage)
                <label style="display: flex; align-items: center; gap: 8px; margin-top: 12px; cursor: pointer; color: #CC0000;">
                    <input type="checkbox" name="hero_background_image_delete" value="1" style="accent-color: #CC0000;">
                    <span style="font-weight: 500;">Supprimer l'image actuelle</span>
                </label>
            @endif
        </div>
    </div>

    {{-- Section: Logos --}}
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
                        $imageUrl = $imagePath ? Storage::url($imagePath) : null;
                        // Check if file exists in public/storage
                        $fileExists = $imagePath && file_exists(public_path('storage/' . $imagePath));
                    @endphp

                    @if($imageUrl && $fileExists)
                        <img src="{{ $imageUrl }}" alt="{{ $logo['label'] }}" class="preview-img">
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
            <i class="fas fa-save"></i> Enregistrer
        </button>
    </div>
</form>

<script>
function toggleHeroOptions() {
    const type = document.querySelector('input[name="hero_background_type"]:checked').value;
    document.getElementById('hero-color-option').style.display = type === 'couleur' ? 'block' : 'none';
    document.getElementById('hero-image-option').style.display = type === 'image' ? 'block' : 'none';
}

// Aperçu en temps réel pour les logos
document.querySelectorAll('input[type="file"][name]').forEach(input => {
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            const previewContainer = this.closest('.logo-card, .hero-bg-preview').querySelector('.logo-preview, .hero-bg-preview');

            reader.onload = function(e) {
                // Supprimer l'ancienne img d'aperçu s'il y en a une
                const existingImg = previewContainer.querySelector('.preview-img');
                if (existingImg) {
                    existingImg.remove();
                }
                // Supprimer le placeholder
                const placeholder = previewContainer.querySelector('.logo-placeholder');
                if (placeholder) {
                    placeholder.remove();
                }
                // Créer la nouvelle img d'aperçu
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-img';
                img.style.cssText = 'width: 100%; height: 100%; object-fit: contain;';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
});

// Aperçu pour le hero background
document.querySelector('input[name="hero_background_image"]')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        const previewContainer = this.closest('#hero-image-option').querySelector('.hero-bg-preview');

        reader.onload = function(e) {
            const existingImg = previewContainer.querySelector('img');
            if (existingImg) {
                existingImg.remove();
            }
            const placeholder = previewContainer.querySelector('div');
            if (placeholder) {
                placeholder.remove();
            }
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = 'Hero background';
            img.style.cssText = 'width: 100%; height: 100%; object-fit: cover;';
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});
</script>

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