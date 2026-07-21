@extends('layouts.app')

@section('title', 'Creer un evenement - Etape 4 sur 4 - ELEDJI')

@section('content')
<div class="create-event-page" x-data="{
    ...mediaUploader(),
    selections: [],
    total: 0,
    toggleOption(key, prix) {
        const index = this.selections.indexOf(key);
        if (index === -1) {
            this.selections.push(key);
            this.total += parseInt(prix);
        } else {
            this.selections.splice(index, 1);
            this.total -= parseInt(prix);
        }
    },
    isSelected(key) {
        return this.selections.includes(key);
    }
}">
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
                <p class="card-subtitle">Boostez la visibilité de votre événement</p>

                <div x-data="{
                    options: {
                        mise_en_avant: false,
                        newsletter: false,
                        reseaux_sociaux: false
                    },
                    prix: {
                        mise_en_avant: {{ setting('premium_mise_en_avant_prix', 5000) }},
                        newsletter: {{ setting('premium_newsletter_prix', 3000) }},
                        reseaux_sociaux: {{ setting('premium_reseaux_prix', 2000) }}
                    },
                    get total() {
                        let t = 0;
                        for (let key in this.options) {
                            if (this.options[key]) t += this.prix[key];
                        }
                        return t;
                    }
                }">
                    {{-- Carte Mise en avant --}}
                    <div @click="options.mise_en_avant = !options.mise_en_avant"
                         :style="options.mise_en_avant ?
                                 'border: 2px solid #CC0000; background: linear-gradient(135deg, #FFF5F5 0%, #FFFFFF 100%); box-shadow: 0 4px 16px rgba(204,0,0,0.12);' :
                                 'border: 1.5px solid #EEEEEE; background: #FFFFFF; box-shadow: 0 2px 8px rgba(0,0,0,0.05);'"
                         style="border-radius: 14px; padding: 18px 20px; margin-bottom: 12px; cursor: pointer;
                                transition: all 0.25s ease; user-select: none; position: relative; overflow: hidden;">

                        <div x-show="options.mise_en_avant"
                             style="position: absolute; top: 0; right: 0; background: #CC0000; color: white;
                                    font-size: 10px; font-weight: 700; padding: 4px 10px;
                                    border-bottom-left-radius: 8px; font-family: 'Poppins', sans-serif;
                                    letter-spacing: 0.5px;">
                            SÉLECTIONNÉ
                        </div>

                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div :style="options.mise_en_avant ?
                                         'background: linear-gradient(135deg, #CC0000, #910000);' :
                                         'background: #F5F5F5;'"
                                 style="width: 44px; height: 44px; border-radius: 10px; display: flex;
                                        align-items: center; justify-content: center; flex-shrink: 0;
                                        transition: all 0.25s ease;">
                                <svg width="20" height="20" viewBox="0 0 24 24"
                                     :fill="options.mise_en_avant ? 'white' : '#AAAAAA'">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>

                            <div style="flex: 1;">
                                <p :style="options.mise_en_avant ? 'color: #CC0000;' : 'color: #222222;'"
                                   style="font-family: 'Poppins', sans-serif; font-size: 14px;
                                          font-weight: 700; margin: 0 0 3px 0; transition: color 0.25s ease;">
                                    Mise en avant sur la page d'accueil
                                </p>
                                <p style="font-family: 'Poppins', sans-serif; font-size: 12px;
                                          color: #888888; margin: 0; line-height: 1.4;">
                                    Votre événement en tête de page pendant 7 jours
                                </p>
                            </div>

                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px; flex-shrink: 0;">
                                <span style="font-family: 'Poppins', sans-serif; font-weight: 800;
                                             font-size: 16px; color: #CC0000;">
                                    {{ number_format(setting('premium_mise_en_avant_prix', 5000), 0, ',', ' ') }}
                                    <span style="font-size: 11px; font-weight: 500; color: #888888;">FCFA</span>
                                </span>
                                <div :style="options.mise_en_avant ?
                                             'background: #CC0000;' :
                                             'background: #DDDDDD;'"
                                     style="width: 40px; height: 22px; border-radius: 11px; position: relative;
                                            transition: background 0.25s ease; flex-shrink: 0;">
                                    <div :style="options.mise_en_avant ? 'transform: translateX(18px);' : 'transform: translateX(2px);'"
                                         style="width: 18px; height: 18px; background: white; border-radius: 50%;
                                                position: absolute; top: 2px; transition: transform 0.25s ease;
                                                box-shadow: 0 1px 4px rgba(0,0,0,0.2);"></div>
                                </div>
                            </div>
                        </div>

                        <input type="checkbox" name="options_premium[]" value="mise_en_avant"
                               :checked="options.mise_en_avant" style="display:none;">
                    </div>

                    {{-- Carte Newsletter --}}
                    <div @click="options.newsletter = !options.newsletter"
                         :style="options.newsletter ?
                                 'border: 2px solid #CC0000; background: linear-gradient(135deg, #FFF5F5 0%, #FFFFFF 100%); box-shadow: 0 4px 16px rgba(204,0,0,0.12);' :
                                 'border: 1.5px solid #EEEEEE; background: #FFFFFF; box-shadow: 0 2px 8px rgba(0,0,0,0.05);'"
                         style="border-radius: 14px; padding: 18px 20px; margin-bottom: 12px; cursor: pointer;
                                transition: all 0.25s ease; user-select: none; position: relative; overflow: hidden;">

                        <div x-show="options.newsletter"
                             style="position: absolute; top: 0; right: 0; background: #CC0000; color: white;
                                    font-size: 10px; font-weight: 700; padding: 4px 10px;
                                    border-bottom-left-radius: 8px; font-family: 'Poppins', sans-serif;
                                    letter-spacing: 0.5px;">
                            SÉLECTIONNÉ
                        </div>

                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div :style="options.newsletter ?
                                         'background: linear-gradient(135deg, #CC0000, #910000);' :
                                         'background: #F5F5F5;'"
                                 style="width: 44px; height: 44px; border-radius: 10px; display: flex;
                                        align-items: center; justify-content: center; flex-shrink: 0;
                                        transition: all 0.25s ease;">
                                <svg width="20" height="20" viewBox="0 0 24 24"
                                     :fill="options.newsletter ? 'white' : '#AAAAAA'">
                                    <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                </svg>
                            </div>

                            <div style="flex: 1;">
                                <p :style="options.newsletter ? 'color: #CC0000;' : 'color: #222222;'"
                                   style="font-family: 'Poppins', sans-serif; font-size: 14px;
                                          font-weight: 700; margin: 0 0 3px 0; transition: color 0.25s ease;">
                                    Publication dans la newsletter
                                </p>
                                <p style="font-family: 'Poppins', sans-serif; font-size: 12px;
                                          color: #888888; margin: 0; line-height: 1.4;">
                                    Envoi à tous les abonnés de la newsletter Eledji
                                </p>
                            </div>

                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px; flex-shrink: 0;">
                                <span style="font-family: 'Poppins', sans-serif; font-weight: 800;
                                             font-size: 16px; color: #CC0000;">
                                    {{ number_format(setting('premium_newsletter_prix', 3000), 0, ',', ' ') }}
                                    <span style="font-size: 11px; font-weight: 500; color: #888888;">FCFA</span>
                                </span>
                                <div :style="options.newsletter ?
                                             'background: #CC0000;' :
                                             'background: #DDDDDD;'"
                                     style="width: 40px; height: 22px; border-radius: 11px; position: relative;
                                            transition: background 0.25s ease; flex-shrink: 0;">
                                    <div :style="options.newsletter ? 'transform: translateX(18px);' : 'transform: translateX(2px);'"
                                         style="width: 18px; height: 18px; background: white; border-radius: 50%;
                                                position: absolute; top: 2px; transition: transform 0.25s ease;
                                                box-shadow: 0 1px 4px rgba(0,0,0,0.2);"></div>
                                </div>
                            </div>
                        </div>

                        <input type="checkbox" name="options_premium[]" value="newsletter"
                               :checked="options.newsletter" style="display:none;">
                    </div>

                    {{-- Carte Réseaux sociaux --}}
                    <div @click="options.reseaux_sociaux = !options.reseaux_sociaux"
                         :style="options.reseaux_sociaux ?
                                 'border: 2px solid #CC0000; background: linear-gradient(135deg, #FFF5F5 0%, #FFFFFF 100%); box-shadow: 0 4px 16px rgba(204,0,0,0.12);' :
                                 'border: 1.5px solid #EEEEEE; background: #FFFFFF; box-shadow: 0 2px 8px rgba(0,0,0,0.05);'"
                         style="border-radius: 14px; padding: 18px 20px; margin-bottom: 12px; cursor: pointer;
                                transition: all 0.25s ease; user-select: none; position: relative; overflow: hidden;">

                        <div x-show="options.reseaux_sociaux"
                             style="position: absolute; top: 0; right: 0; background: #CC0000; color: white;
                                    font-size: 10px; font-weight: 700; padding: 4px 10px;
                                    border-bottom-left-radius: 8px; font-family: 'Poppins', sans-serif;
                                    letter-spacing: 0.5px;">
                            SÉLECTIONNÉ
                        </div>

                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div :style="options.reseaux_sociaux ?
                                         'background: linear-gradient(135deg, #CC0000, #910000);' :
                                         'background: #F5F5F5;'"
                                 style="width: 44px; height: 44px; border-radius: 10px; display: flex;
                                        align-items: center; justify-content: center; flex-shrink: 0;
                                        transition: all 0.25s ease;">
                                <svg width="20" height="20" viewBox="0 0 24 24"
                                     :fill="options.reseaux_sociaux ? 'white' : '#AAAAAA'">
                                    <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92-1.31-2.92-2.92-2.92z"/>
                                </svg>
                            </div>

                            <div style="flex: 1;">
                                <p :style="options.reseaux_sociaux ? 'color: #CC0000;' : 'color: #222222;'"
                                   style="font-family: 'Poppins', sans-serif; font-size: 14px;
                                          font-weight: 700; margin: 0 0 3px 0; transition: color 0.25s ease;">
                                    Partage sur les réseaux sociaux
                                </p>
                                <p style="font-family: 'Poppins', sans-serif; font-size: 12px;
                                          color: #888888; margin: 0; line-height: 1.4;">
                                    Publication sur Facebook et Instagram d'Eledji
                                </p>
                            </div>

                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px; flex-shrink: 0;">
                                <span style="font-family: 'Poppins', sans-serif; font-weight: 800;
                                             font-size: 16px; color: #CC0000;">
                                    {{ number_format(setting('premium_reseaux_prix', 2000), 0, ',', ' ') }}
                                    <span style="font-size: 11px; font-weight: 500; color: #888888;">FCFA</span>
                                </span>
                                <div :style="options.reseaux_sociaux ?
                                             'background: #CC0000;' :
                                             'background: #DDDDDD;'"
                                     style="width: 40px; height: 22px; border-radius: 11px; position: relative;
                                            transition: background 0.25s ease; flex-shrink: 0;">
                                    <div :style="options.reseaux_sociaux ? 'transform: translateX(18px);' : 'transform: translateX(2px);'"
                                         style="width: 18px; height: 18px; background: white; border-radius: 50%;
                                                position: absolute; top: 2px; transition: transform 0.25s ease;
                                                box-shadow: 0 1px 4px rgba(0,0,0,0.2);"></div>
                                </div>
                            </div>
                        </div>

                        <input type="checkbox" name="options_premium[]" value="reseaux_sociaux"
                               :checked="options.reseaux_sociaux" style="display:none;">
                    </div>

                    {{-- Total --}}
                    <div x-show="total > 0" x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         style="margin-top: 16px; padding: 16px 20px;
                                background: linear-gradient(135deg, #FFF0F0, #FFFFFF);
                                border-radius: 12px; border: 1.5px solid #FFCCCC;
                                display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p style="font-family: 'Poppins', sans-serif; font-size: 12px;
                                      color: #888888; margin: 0 0 2px 0; font-weight: 500;">
                                Total à payer pour les options
                            </p>
                            <p style="font-family: 'Poppins', sans-serif; font-size: 11px;
                                      color: #AAAAAA; margin: 0;">
                                Paiement sécurisé après publication
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-family: 'Poppins', sans-serif; font-weight: 800;
                                         font-size: 22px; color: #CC0000;"
                                  x-text="total.toLocaleString('fr-FR')"></span>
                            <span style="font-family: 'Poppins', sans-serif; font-size: 13px;
                                         color: #CC0000; font-weight: 600;">FCFA</span>
                        </div>
                    </div>
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
                        <span class="recap-label">Billetterie</span>
                        <span class="recap-value">
                            @if(session('event_step3.type_evenement') === 'gratuit')
                                <span style="background: #2E7D32; color: white; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    Gratuit
                                </span>
                            @else
                                @forelse($billetsActifs as $billet)
                                    <div style="margin-bottom: 6px; display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                        <span style="background: {{ $billet['color'] }}; color: white; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 700;">
                                            {{ $billet['type'] }}
                                        </span>
                                        <span style="color: #CC0000; font-weight: 700;">
                                            @if($billet['prix'] > 0)
                                                {{ number_format($billet['prix'], 0, ',', ' ') }} FCF
                                            @else
                                                Gratuit
                                            @endif
                                        </span>
                                    </div>
                                @empty
                                    <span style="color: #CC0000;">Aucun billet configure</span>
                                @endforelse
                            @endif
                        </span>
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
    .create-event-page {
        padding: 100px 16px 40px;
    }

    .form-card {
        padding: 20px 16px;
    }

    .publish-options {
        grid-template-columns: 1fr;
    }

    .create-event-header {
        margin-bottom: 28px;
    }

    .create-event-header h1 {
        font-size: 20px;
    }

    .form-navigation {
        flex-direction: column-reverse;
        gap: 12px;
        margin-top: 8px;
    }

    .btn {
        width: 100%;
        padding: 16px;
        min-height: 52px;
        font-size: 15px;
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