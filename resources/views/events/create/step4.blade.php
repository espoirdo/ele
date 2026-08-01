@extends('layouts.app')

@section('title', 'Creer un evenement - Etape 4 sur 4 - ELEDJI')

@section('content')
<div class="create-event-page" x-data="eventStep4()" x-cloak>
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

            {{-- Image de couverture --}}
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

            {{-- Options premium --}}
            <div class="form-card">
                <h3 class="card-title">Options premium</h3>
                <p class="card-subtitle">Boostez la visibilité de votre événement</p>

                @foreach([
                    ['key' => 'mise_en_avant', 'label' => 'Mise en avant sur la page d\'accueil', 'desc' => 'Votre événement en tête de page pendant 7 jours', 'prix_key' => 'premium_mise_en_avant_prix', 'default' => 5000],
                    ['key' => 'newsletter', 'label' => 'Publication dans la newsletter', 'desc' => 'Envoi à tous les abonnés de la newsletter Eledji', 'prix_key' => 'premium_newsletter_prix', 'default' => 3000],
                    ['key' => 'reseaux_sociaux', 'label' => 'Partage sur les réseaux sociaux', 'desc' => 'Publication sur les pages Facebook et Instagram d\'Eledji', 'prix_key' => 'premium_reseaux_prix', 'default' => 2000],
                ] as $opt)
                    <div @click="togglePremium('{{ $opt['key'] }}')"
                         :style="premiumOptions.{{ $opt['key'] }} ?
                                 'border: 1.5px solid #CC0000; background: #FFFAFA;' :
                                 'border: 1.5px solid #EEEEEE; background: #FFFFFF;'"
                         style="border-radius: 12px; padding: 16px 20px; margin-bottom: 10px;
                                cursor: pointer; transition: all 0.2s ease;
                                display: flex; align-items: center; gap: 14px;
                                user-select: none; box-shadow: 0 1px 4px rgba(0,0,0,0.04);">

                        <div :style="premiumOptions.{{ $opt['key'] }} ?
                                     'background: #CC0000; border-color: #CC0000;' :
                                     'background: white; border-color: #DDDDDD;'"
                             style="width: 18px; height: 18px; border: 2px solid #DDDDDD;
                                    border-radius: 4px; display: flex; align-items: center;
                                    justify-content: center; flex-shrink: 0; transition: all 0.2s ease;">
                            <svg x-show="premiumOptions.{{ $opt['key'] }}" width="10" height="8"
                                 viewBox="0 0 10 8" fill="none">
                                <path d="M1 4L3.5 6.5L9 1" stroke="white" stroke-width="1.8"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <div style="flex: 1; min-width: 0;">
                            <p style="font-family: 'Poppins', sans-serif; font-size: 14px;
                                      font-weight: 600; color: #222222; margin: 0 0 2px 0;">
                                {{ $opt['label'] }}
                            </p>
                            <p style="font-family: 'Poppins', sans-serif; font-size: 12px;
                                      color: #999999; margin: 0;">
                                {{ $opt['desc'] }}
                            </p>
                        </div>

                        <div style="text-align: right; flex-shrink: 0;">
                            <span style="font-family: 'Poppins', sans-serif; font-weight: 700;
                                         font-size: 15px; color: #CC0000;">
                                {{ number_format(setting($opt['prix_key'], $opt['default']), 0, ',', ' ') }}
                            </span>
                            <span style="font-family: 'Poppins', sans-serif; font-size: 11px;
                                         color: #999999; margin-left: 2px;">FCFA</span>
                        </div>

                        <input type="checkbox" name="options_premium[]"
                               value="{{ $opt['key'] }}"
                               :checked="premiumOptions.{{ $opt['key'] }}"
                               style="display: none;">
                    </div>
                @endforeach

                <div x-show="premiumTotal > 0" x-transition
                     style="margin-top: 4px; padding: 14px 20px; background: #F9F9F9;
                            border: 1px solid #EEEEEE; border-radius: 10px;
                            display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-family: 'Poppins', sans-serif; font-size: 13px;
                                 color: #666666; font-weight: 500;">
                        Total options premium
                    </span>
                    <span style="font-family: 'Poppins', sans-serif; font-weight: 700;
                                 font-size: 18px; color: #CC0000;"
                          x-text="premiumTotal.toLocaleString('fr-FR') + ' FCFA'">
                    </span>
                </div>
            </div>

            {{-- Badge J'y serai --}}
            <div class="form-card">
                <h3 class="card-title">Badge "J'y serai"</h3>

                {{-- Toggle --}}
                <div @click="toggleBadge()"
                     :style="badgeActif ?
                             'border: 1.5px solid #CC0000; background: #FFFAFA;' :
                             'border: 1.5px solid #EEEEEE; background: #FAFAFA;'"
                     style="display: flex; align-items: center; gap: 14px;
                            margin-bottom: 16px; padding: 16px;
                            border-radius: 12px; cursor: pointer;
                            transition: all 0.2s ease; user-select: none;
                            box-shadow: 0 1px 4px rgba(0,0,0,0.04);">

                    <div :style="badgeActif ?
                                 'background: #CC0000; border-color: #CC0000;' :
                                 'background: white; border-color: #DDDDDD;'"
                         style="width: 18px; height: 18px; border: 2px solid #DDDDDD;
                                border-radius: 4px; display: flex; align-items: center;
                                justify-content: center; flex-shrink: 0;
                                transition: all 0.2s ease; pointer-events: none;">
                        <svg x-show="badgeActif" width="10" height="8"
                             viewBox="0 0 10 8" fill="none">
                            <path d="M1 4L3.5 6.5L9 1" stroke="white" stroke-width="1.8"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <div style="flex: 1; min-width: 0;">
                        <p style="font-family: 'Poppins', sans-serif; font-size: 14px;
                                  font-weight: 600; color: #1a1a1a; margin: 0 0 2px 0;">
                            Activer le badge "J'y serai"
                        </p>
                        <p style="font-family: 'Poppins', sans-serif; font-size: 12px;
                                  color: #888888; margin: 0;">
                            Permettez à vos participants de créer un visuel personnalisé à partager
                        </p>
                    </div>

                    <input type="checkbox" name="badge_actif" value="1"
                           :checked="badgeActif" style="display: none;">
                </div>

                {{-- Section upload affiche (visible si badge activé) --}}
                <div x-show="badgeActif" x-transition style="margin-top: 20px;">
                    <div class="upload-zone"
                         :class="affichePreview ? 'has-image' : ''"
                         style="border: 2px dashed #CC0000; background: #FFF5F5; border-radius: 12px; height: 200px; display: flex; align-items: center; justify-content: center; position: relative;">
                        <template x-if="!affichePreview">
                            <div style="text-align: center; color: #CC0000; pointer-events: none;">
                                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 8px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p style="font-family: 'Poppins', sans-serif; font-size: 13px; margin: 0; font-weight: 600;">
                                    Uploadez l'affiche officielle (PNG, JPG, max 10MB)
                                </p>
                            </div>
                        </template>
                        <template x-if="affichePreview">
                            <div style="width: 100%; height: 100%; position: relative;">
                                <img :src="affichePreview" alt="Affiche" style="width: 100%; height: 100%; object-fit: contain; border-radius: 10px;">
                                <button type="button" @click.stop="removeAffiche()"
                                        style="position: absolute; top: 8px; right: 8px; width: 32px; height: 32px; border-radius: 50%; background: rgba(0,0,0,0.7); color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 5;">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <input type="file"
                               name="affiche_officielle"
                               accept="image/png,image/jpeg"
                               @change="loadAffiche($event)"
                               style="position: absolute; inset: 0; opacity: 0; cursor: pointer; z-index: 3;">
                    </div>

                    {{-- Zone de positionnement (visible quand image chargée) --}}
                    <div x-show="imageLoaded" x-transition style="margin-top: 24px;">
                        <h4 style="font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600; color: #1a1a1a; margin: 0 0 12px;">
                            Positionnez la zone de la photo
                        </h4>

                        <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                            <button type="button"
                                    @click="setZoneType('cercle')"
                                    :class="zoneType === 'cercle' ? 'zone-type-btn active' : 'zone-type-btn'"
                                    style="padding: 10px 20px; border: 2px solid #ddd; border-radius: 8px;
                                           font-family: 'Poppins', sans-serif; font-size: 13px;
                                           font-weight: 600; cursor: pointer; transition: all 0.2s;
                                           background: white; color: #666;">
                                Cercle
                            </button>
                            <button type="button"
                                    @click="setZoneType('rectangle')"
                                    :class="zoneType === 'rectangle' ? 'zone-type-btn active' : 'zone-type-btn'"
                                    style="padding: 10px 20px; border: 2px solid #ddd; border-radius: 8px;
                                           font-family: 'Poppins', sans-serif; font-size: 13px;
                                           font-weight: 600; cursor: pointer; transition: all 0.2s;
                                           background: white; color: #666;">
                                Rectangle
                            </button>
                        </div>

                        <p style="font-family: 'Poppins', sans-serif; font-size: 12px; color: #888; margin-bottom: 12px;">
                            Faites glisser le cadre rouge pour le positionner. Utilisez les coins pour le redimensionner.
                        </p>

                        <div style="display: inline-block; position: relative; max-width: 100%;">
                            <canvas id="badge-canvas"
                                    @mousedown="handleCanvasMouseDown($event)"
                                    @mousemove="handleCanvasMouseMove($event)"
                                    @mouseup="handleCanvasMouseUp()"
                                    @mouseleave="handleCanvasMouseUp()"
                                    @touchstart.prevent="handleCanvasMouseDown($event)"
                                    @touchmove.prevent="handleCanvasMouseMove($event)"
                                    @touchend="handleCanvasMouseUp()"
                                    style="max-width: 100%; border-radius: 8px; cursor: move;
                                           box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: block; touch-action: none;">
                            </canvas>
                        </div>

                        <input type="hidden" id="badge_zone_type" name="badge_zone_type" :value="zoneType">
                        <input type="hidden" id="badge_zone_x" name="badge_zone_x" :value="zoneX">
                        <input type="hidden" id="badge_zone_y" name="badge_zone_y" :value="zoneY">
                        <input type="hidden" id="badge_zone_width" name="badge_zone_width" :value="zoneWidth">
                        <input type="hidden" id="badge_zone_height" name="badge_zone_height" :value="zoneHeight">
                    </div>
                </div>
            </div>

            {{-- Statut de publication --}}
            <div class="form-card">
                <h3 class="card-title">Statut de publication</h3>

                <div class="publish-options">
                    <label class="publish-card"
                           :class="statut === 'brouillon' ? 'selected' : ''"
                           @click.prevent="statut = 'brouillon'"
                           style="cursor: pointer;">
                        <input type="radio"
                               name="statut"
                               value="brouillon"
                               x-model="statut"
                               style="position: absolute; opacity: 0; pointer-events: none; width: 0; height: 0;">
                        <div class="publish-info">
                            <span class="publish-name">Brouillon</span>
                            <span class="publish-desc">Enregistrer et publier plus tard</span>
                        </div>
                        <div class="publish-check">
                            <svg x-show="statut === 'brouillon'" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </label>

                    <label class="publish-card"
                           :class="statut === 'publie' ? 'selected' : ''"
                           @click.prevent="statut = 'publie'"
                           style="cursor: pointer;">
                        <input type="radio"
                               name="statut"
                               value="publie"
                               x-model="statut"
                               style="position: absolute; opacity: 0; pointer-events: none; width: 0; height: 0;">
                        <div class="publish-info">
                            <span class="publish-name">Publier maintenant</span>
                            <span class="publish-desc">Rendre visible immediatement</span>
                        </div>
                        <div class="publish-check">
                            <svg x-show="statut === 'publie'" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Recapitulatif --}}
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
                        <span class="recap-value">
                            @if(!empty($step2['date_fin']) && ($step2['date_fin'] ?? '') !== ($step2['date'] ?? ''))
                                Du {{ $step2['date'] ?? '' }} au {{ $step2['date_fin'] ?? '' }}<br>
                            @else
                                Le {{ $step2['date'] ?? '' }}<br>
                            @endif
                            <span style="color:#666;font-weight:500">
                                de {{ $step2['heure_debut'] ?? '' }} a {{ $step2['heure_fin'] ?? '' }}
                            </span>
                        </span>
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
                                                {{ number_format($billet['prix'], 0, ',', ' ') }} FCFA
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
[x-cloak] { display: none !important; }

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
    z-index: 2;
}

.upload-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    color: #CC0000;
    pointer-events: none;
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
    z-index: 5;
}

.remove-image:hover {
    background: #CC0000;
}

.zone-type-btn.active {
    background: #CC0000 !important;
    color: white !important;
    border-color: #CC0000 !important;
}

.publish-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.publish-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border: 2px solid #E0E0E0;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.25s ease;
    background: white;
}

.publish-card:hover {
    border-color: #CC0000;
}

.publish-card.selected {
    border-color: #CC0000;
    background: rgba(204, 0, 0, 0.05);
}

.publish-info {
    flex: 1;
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

.publish-check {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 2px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
    background: white;
}

.publish-card.selected .publish-check {
    background: #1E88E5;
    border-color: #1E88E5;
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
function eventStep4() {
    return {
        // === Image de couverture ===
        imagePreview: null,

        // === Options premium ===
        premiumOptions: {
            mise_en_avant: false,
            newsletter: false,
            reseaux_sociaux: false,
        },
        premiumPrices: {
            mise_en_avant: {{ setting('premium_mise_en_avant_prix', 5000) }},
            newsletter: {{ setting('premium_newsletter_prix', 3000) }},
            reseaux_sociaux: {{ setting('premium_reseaux_prix', 2000) }},
        },
        get premiumTotal() {
            let t = 0;
            for (const key in this.premiumOptions) {
                if (this.premiumOptions[key]) t += this.premiumPrices[key] || 0;
            }
            return t;
        },
        togglePremium(key) {
            this.premiumOptions[key] = !this.premiumOptions[key];
        },
        toggleBadge() {
            this.badgeActif = !this.badgeActif;
        },

        // === Badge J'y serai ===
        badgeActif: false,
        affichePreview: null,
        imageLoaded: false,
        zoneType: 'cercle',
        zoneX: 50,
        zoneY: 50,
        zoneWidth: 30,
        zoneHeight: 30,
        isDragging: false,
        isResizing: false,
        resizeCorner: null,
        dragStart: { x: 0, y: 0 },
        zoneStart: { x: 50, y: 50, w: 30, h: 30 },
        canvasImage: null,

        // === Statut ===
        statut: 'publie',

        // === Image de couverture handlers ===
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) this.previewFile(file);
        },

        dragOver(event) {
            event.currentTarget.classList.add('drag-over');
        },

        dragLeave(event) {
            event.currentTarget.classList.remove('drag-over');
        },

        drop(event) {
            const zone = event.currentTarget;
            zone.classList.remove('drag-over');
            const file = event.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                const input = document.getElementById('image_couverture');
                if (input) {
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    input.files = dt.files;
                }
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
            const input = document.getElementById('image_couverture');
            if (input) input.value = '';
        },

        // === Badge handlers ===
        loadAffiche(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 10 * 1024 * 1024) {
                alert('Image trop grande. Maximum 10MB.');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                this.affichePreview = e.target.result;
                const img = new Image();
                img.onload = () => {
                    this.canvasImage = img;
                    this.imageLoaded = true;
                    this.$nextTick(() => this.drawCanvas());
                };
                img.onerror = () => {
                    alert("Impossible de charger l'image.");
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        removeAffiche() {
            this.affichePreview = null;
            this.imageLoaded = false;
            this.canvasImage = null;
        },

        setZoneType(type) {
            this.zoneType = type;
            this.drawCanvas();
        },

        drawCanvas() {
            if (!this.canvasImage || !this.imageLoaded) return;
            const canvas = document.getElementById('badge-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            const maxWidth = 600;
            const scale = Math.min(1, maxWidth / this.canvasImage.width);
            const w = this.canvasImage.width * scale;
            const h = this.canvasImage.height * scale;
            canvas.width = w;
            canvas.height = h;

            ctx.clearRect(0, 0, w, h);
            ctx.drawImage(this.canvasImage, 0, 0, w, h);

            const x = (this.zoneX / 100) * w;
            const y = (this.zoneY / 100) * h;
            const zw = (this.zoneWidth / 100) * w;
            const zh = (this.zoneHeight / 100) * h;

            ctx.fillStyle = 'rgba(204, 0, 0, 0.35)';
            ctx.strokeStyle = '#CC0000';
            ctx.lineWidth = 2;

            if (this.zoneType === 'cercle') {
                ctx.beginPath();
                ctx.arc(x, y, Math.min(zw, zh) / 2, 0, Math.PI * 2);
                ctx.fill();
                ctx.stroke();
            } else {
                ctx.fillRect(x - zw / 2, y - zh / 2, zw, zh);
                ctx.strokeRect(x - zw / 2, y - zh / 2, zw, zh);
            }

            if (this.zoneType === 'rectangle') {
                const handleSize = 8;
                ctx.fillStyle = '#CC0000';
                const corners = [
                    [x - zw / 2, y - zh / 2],
                    [x + zw / 2, y - zh / 2],
                    [x - zw / 2, y + zh / 2],
                    [x + zw / 2, y + zh / 2],
                ];
                corners.forEach(([cx, cy]) => {
                    ctx.fillRect(cx - handleSize / 2, cy - handleSize / 2, handleSize, handleSize);
                });
            }
        },

        getCanvasCoords(e) {
            const canvas = document.getElementById('badge-canvas');
            if (!canvas) return null;
            const rect = canvas.getBoundingClientRect();
            const touch = e.touches ? e.touches[0] : e;
            return {
                x: (touch.clientX - rect.left) * (canvas.width / rect.width),
                y: (touch.clientY - rect.top) * (canvas.height / rect.height),
            };
        },

        handleCanvasMouseDown(e) {
            if (!this.imageLoaded) return;
            const coords = this.getCanvasCoords(e);
            if (!coords) return;
            const canvas = document.getElementById('badge-canvas');
            if (!canvas) return;

            const x = (this.zoneX / 100) * canvas.width;
            const y = (this.zoneY / 100) * canvas.height;
            const zw = (this.zoneWidth / 100) * canvas.width;
            const zh = (this.zoneHeight / 100) * canvas.height;

            if (this.zoneType === 'rectangle') {
                const handleSize = 12;
                const corners = [
                    { cx: x - zw / 2, cy: y - zh / 2 },
                    { cx: x + zw / 2, cy: y - zh / 2 },
                    { cx: x - zw / 2, cy: y + zh / 2 },
                    { cx: x + zw / 2, cy: y + zh / 2 },
                ];
                for (let i = 0; i < corners.length; i++) {
                    if (Math.abs(coords.x - corners[i].cx) < handleSize &&
                        Math.abs(coords.y - corners[i].cy) < handleSize) {
                        this.isResizing = true;
                        this.resizeCorner = i;
                        this.dragStart = coords;
                        this.zoneStart = { x: this.zoneX, y: this.zoneY, w: this.zoneWidth, h: this.zoneHeight };
                        return;
                    }
                }
            }

            let inside = false;
            if (this.zoneType === 'cercle') {
                const r = Math.min(zw, zh) / 2;
                const dist = Math.sqrt((coords.x - x) ** 2 + (coords.y - y) ** 2);
                inside = dist <= r;
            } else {
                inside = coords.x >= x - zw / 2 && coords.x <= x + zw / 2 &&
                         coords.y >= y - zh / 2 && coords.y <= y + zh / 2;
            }

            if (inside) {
                this.isDragging = true;
                this.dragStart = coords;
                this.zoneStart = { x: this.zoneX, y: this.zoneY, w: this.zoneWidth, h: this.zoneHeight };
            }
        },

        handleCanvasMouseMove(e) {
            if (!this.isDragging && !this.isResizing) return;
            const coords = this.getCanvasCoords(e);
            if (!coords) return;
            const canvas = document.getElementById('badge-canvas');
            if (!canvas) return;

            if (this.isDragging) {
                const dx = (coords.x - this.dragStart.x) / canvas.width * 100;
                const dy = (coords.y - this.dragStart.y) / canvas.height * 100;
                this.zoneX = Math.max(0, Math.min(100, this.zoneStart.x + dx));
                this.zoneY = Math.max(0, Math.min(100, this.zoneStart.y + dy));
            } else if (this.isResizing) {
                const dx = (coords.x - this.dragStart.x) / canvas.width * 100;
                const dy = (coords.y - this.dragStart.y) / canvas.height * 100;

                if (this.resizeCorner === 0) {
                    this.zoneWidth = Math.max(10, this.zoneStart.w - dx);
                    this.zoneHeight = Math.max(10, this.zoneStart.h - dy);
                    this.zoneX = this.zoneStart.x + dx / 2;
                    this.zoneY = this.zoneStart.y + dy / 2;
                } else if (this.resizeCorner === 1) {
                    this.zoneWidth = Math.max(10, this.zoneStart.w + dx);
                    this.zoneHeight = Math.max(10, this.zoneStart.h - dy);
                    this.zoneY = this.zoneStart.y + dy / 2;
                } else if (this.resizeCorner === 2) {
                    this.zoneWidth = Math.max(10, this.zoneStart.w - dx);
                    this.zoneHeight = Math.max(10, this.zoneStart.h + dy);
                    this.zoneX = this.zoneStart.x + dx / 2;
                } else if (this.resizeCorner === 3) {
                    this.zoneWidth = Math.max(10, this.zoneStart.w + dx);
                    this.zoneHeight = Math.max(10, this.zoneStart.h + dy);
                }

                this.zoneX = Math.max(0, Math.min(100, this.zoneX));
                this.zoneY = Math.max(0, Math.min(100, this.zoneY));
                this.zoneWidth = Math.max(10, Math.min(100, this.zoneWidth));
                this.zoneHeight = Math.max(10, Math.min(100, this.zoneHeight));
            }

            this.drawCanvas();
        },

        handleCanvasMouseUp() {
            this.isDragging = false;
            this.isResizing = false;
            this.resizeCorner = null;
        },
    };
}
</script>
@endpush
@endsection
