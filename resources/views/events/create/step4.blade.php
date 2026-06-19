@extends('layouts.app')

@section('title', 'Creer un evenement - Etape 4 sur 4 - ELEDJI')

@section('content')
<div class="create-event-page" x-data="mediaUploader()">
    <div class="create-event-container">
        {{-- Progress Bar --}}
        @include('events.create.progress-bar', ['currentStep' => 4])

        {{-- Header --}}
        <div class="create-event-header">
            <h1>Creer un evenement - Etape 4 sur 4</h1>
            <p>Medias et publication</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('events.create.step4.post') }}" method="POST" class="create-event-form" enctype="multipart/form-data">
            @csrf

            <div class="form-card">
                <h3 class="card-title">Image de couverture</h3>

                <div class="upload-zone"
                     :class="imagePreview ? 'has-image' : ''"
                     @dragover.prevent="dragOver($event)"
                     @dragleave.prevent="dragLeave($event)"
                     @drop.prevent="drop($event)">
                    <template x-if="!imagePreview">
                        <div class="upload-content">
                            <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="upload-text">Glissez ou selectionnez une image</p>
                            <p class="upload-hint">JPG, JPEG, PNG, WEBP - max 5MB</p>
                        </div>
                    </template>
                    <template x-if="imagePreview">
                        <div class="image-preview">
                            <img :src="imagePreview" alt="Apercu">
                            <button type="button" class="remove-image" @click="removeImage()">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                    <input type="file"
                           id="image_couverture"
                           name="image_couverture"
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           @change="handleFileSelect($event)">
                </div>
            </div>

            <div class="form-card">
                <h3 class="card-title">Options premium</h3>

                <div class="premium-options">
                    @foreach($premiumOptions as $option)
                        <label class="premium-card" :class="selectedOptions.includes({{ $option->id }}) ? 'selected' : ''">
                            <input type="checkbox"
                                   name="premium_options[]"
                                   value="{{ $option->id }}"
                                   x-model="selectedOptions">
                            <div class="premium-info">
                                <span class="premium-name">{{ $option->titre }}</span>
                                <span class="premium-desc">{{ $option->description }}</span>
                            </div>
                            <span class="premium-price">{{ number_format($option->tarif, 0, ',', ' ') }} XOF</span>
                        </label>
                    @endforeach
                </div>

                <div class="premium-checkboxes">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="premium_mise_en_avant" value="1" x-model="miseEnAvant">
                        Mise en avant sur la page d'accueil
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" name="premium_newsletter" value="1" x-model="newsletter">
                        Publication dans la newsletter
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" name="premium_reseaux" value="1" x-model="reseaux">
                        Partage sur les reseaux sociaux
                    </label>
                </div>
            </div>

            <div class="form-card">
                <h3 class="card-title">Statut de publication</h3>

                <div class="publish-options">
                    <label class="publish-card" :class="statut === 'brouillon' ? 'selected' : ''">
                        <input type="radio" name="statut" value="brouillon" x-model="statut">
                        <div class="publish-info">
                            <span class="publish-name">Brouillon</span>
                            <span class="publish-desc">Enregistrer et publier plus tard</span>
                        </div>
                    </label>

                    <label class="publish-card" :class="statut === 'publie' ? 'selected' : ''">
                        <input type="radio" name="statut" value="publie" x-model="statut">
                        <div class="publish-info">
                            <span class="publish-name">Publier maintenant</span>
                            <span class="publish-desc">Rendre visible immediatement</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-card">
                <h3 class="card-title">Recapitulatif</h3>

                <div class="recap">
                    <div class="recap-item">
                        <span class="recap-label">Titre</span>
                        <span class="recap-value">{{ $step1['titre'] ?? '' }}</span>
                    </div>
                    <div class="recap-item">
                        <span class="recap-label">Description</span>
                        <span class="recap-value">{{ Str::limit($step1['description'] ?? '', 100) }}</span>
                    </div>
                    <div class="recap-item">
                        <span class="recap-label">Lieu</span>
                        <span class="recap-value">{{ $step2['lieu'] ?? '' }}</span>
                    </div>
                    <div class="recap-item">
                        <span class="recap-label">Date</span>
                        <span class="recap-value">{{ $step2['date'] ?? '' }} de {{ $step2['heure_debut'] ?? '' }} a {{ $step2['heure_fin'] ?? '' }}</span>
                    </div>
                    <div class="recap-item">
                        <span class="recap-label">Prix</span>
                        <span class="recap-value">{{ $step3['est_gratuit'] ?? '1' == '1' ? 'Gratuit' : number_format($step3['prix'] ?? 0, 0, ',', ' ') . ' XOF' }}</span>
                    </div>
                    <div class="recap-item">
                        <span class="recap-label">Places</span>
                        <span class="recap-value">{{ $step3['nombre_places'] ?? '' }}</span>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <div class="form-navigation">
                <a href="{{ route('events.create.step3') }}" class="btn btn-secondary btn-prev">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Precedent
                </a>
                <button type="submit" class="btn btn-primary btn-publish">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Publier l'evenement
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.create-event-page {
    min-height: calc(100vh - 200px);
    padding: 120px 24px 60px;
    background: #F9F9F9;
}

.create-event-container {
    max-width: 720px;
    margin: 0 auto;
}

.create-event-header {
    text-align: center;
    margin-bottom: 40px;
}

.create-event-header h1 {
    font-family: 'Eras Medium ITC', serif;
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.create-event-header p {
    font-size: 14px;
    color: #666;
}

.form-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    padding: 32px;
    margin-bottom: 24px;
}

.card-title {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 16px;
}

.upload-zone {
    position: relative;
    border: 2px dashed #CC0000;
    border-radius: 12px;
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s ease;
    background: #FDF5F5;
}

.upload-zone:hover {
    background: #FEE2E2;
}

.upload-zone.has-image {
    padding: 0;
    background: transparent;
    border-style: solid;
}

.upload-zone input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
}

.upload-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    color: #CC0000;
}

.upload-text {
    font-size: 14px;
    font-weight: 600;
}

.upload-hint {
    font-size: 12px;
    color: #888;
}

.image-preview {
    position: relative;
    width: 100%;
}

.image-preview img {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 10px;
}

.remove-image {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.25s ease;
}

.remove-image:hover {
    background: #CC0000;
}

.premium-options {
    display: grid;
    gap: 12px;
    margin-bottom: 20px;
}

.premium-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px;
    border: 1.5px solid #E0E0E0;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.25s ease;
}

.premium-card:hover {
    border-color: #CC0000;
}

.premium-card.selected {
    border-color: #CC0000;
    background: rgba(204, 0, 0, 0.05);
}

.premium-card input {
    display: none;
}

.premium-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.premium-name {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a;
}

.premium-desc {
    font-size: 12px;
    color: #666;
}

.premium-price {
    font-size: 14px;
    font-weight: 700;
    color: #CC0000;
}

.premium-checkboxes {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.checkbox-inline {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #555;
    cursor: pointer;
}

.checkbox-inline input {
    width: 18px;
    height: 18px;
    accent-color: #CC0000;
}

.publish-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.publish-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border: 2px solid #E0E0E0;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.25s ease;
}

.publish-card:hover {
    border-color: #CC0000;
}

.publish-card.selected {
    border-color: #CC0000;
    background: rgba(204, 0, 0, 0.05);
}

.publish-card input {
    display: none;
}

.publish-info {
    display: flex;
    flex-direction: column;
}

.publish-name {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a;
}

.publish-desc {
    font-size: 12px;
    color: #666;
}

.recap {
    background: #F9F9F9;
    border-radius: 10px;
    padding: 16px;
}

.recap-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #E8E8E8;
}

.recap-item:last-child {
    border-bottom: none;
}

.recap-label {
    font-size: 13px;
    color: #666;
}

.recap-value {
    font-size: 13px;
    font-weight: 600;
    color: #1a1a1a;
    text-align: right;
    max-width: 60%;
}

.form-navigation {
    display: flex;
    justify-content: space-between;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 28px;
    border-radius: 40px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
    border: none;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

.btn-primary {
    background: linear-gradient(135deg, #CC0000, #910000);
    color: white;
    min-width: 220px;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(204, 0, 0, 0.35);
}

.btn-secondary {
    background: white;
    color: #555;
    border: 1.5px solid #E0E0E0;
}

.btn-secondary:hover {
    background: #F5F5F5;
}

.btn-prev {
    min-width: 140px;
}

.btn-publish {
    min-width: 220px;
}

@media (max-width: 600px) {
    .form-card {
        padding: 24px 20px;
    }

    .publish-options {
        grid-template-columns: 1fr;
    }

    .create-event-header h1 {
        font-size: 22px;
    }

    .form-navigation {
        flex-direction: column-reverse;
        gap: 12px;
    }

    .btn {
        width: 100%;
    }

    .btn-publish {
        min-width: auto;
    }
}
</style>
@endpush

@push('scripts')
<script>
function mediaUploader() {
    return {
        imagePreview: null,
        selectedOptions: [],
        miseEnAvant: false,
        newsletter: false,
        reseaux: false,
        statut: 'publie',

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.previewFile(file);
            }
        },

        dragOver(event) {
            event.target.closest('.upload-zone').classList.add('drag-over');
        },

        dragLeave(event) {
            event.target.closest('.upload-zone').classList.remove('drag-over');
        },

        drop(event) {
            const zone = event.target.closest('.upload-zone');
            zone.classList.remove('drag-over');
            const file = event.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                document.getElementById('image_couverture').files = event.dataTransfer.files;
                this.previewFile(file);
            }
        },

        previewFile(file) {
            if (file.size > 5 * 1024 * 1024) {
                alert('Image trop grande. Maximum 5MB.');
                return;
            }
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imagePreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        removeImage() {
            this.imagePreview = null;
            document.getElementById('image_couverture').value = '';
        }
    };
}
</script>
@endpush
@endsection