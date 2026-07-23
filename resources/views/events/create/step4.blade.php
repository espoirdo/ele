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
                }" style="display: flex; flex-direction: column; gap: 10px;">

                    @foreach([
                        ['key' => 'mise_en_avant', 'label' => 'Mise en avant sur la page d\'accueil', 'desc' => 'Votre événement en tête de page pendant 7 jours', 'prix_key' => 'premium_mise_en_avant_prix', 'default' => 5000],
                        ['key' => 'newsletter', 'label' => 'Publication dans la newsletter', 'desc' => 'Envoi à tous les abonnés de la newsletter Eledji', 'prix_key' => 'premium_newsletter_prix', 'default' => 3000],
                        ['key' => 'reseaux_sociaux', 'label' => 'Partage sur les réseaux sociaux', 'desc' => 'Publication sur les pages Facebook et Instagram d\'Eledji', 'prix_key' => 'premium_reseaux_prix', 'default' => 2000],
                    ] as $opt)

                    <div @click="options.{{ $opt['key'] }} = !options.{{ $opt['key'] }}"
                         :style="options.{{ $opt['key'] }} ?
                                 'border: 1.5px solid #CC0000; background: #FFFAFA;' :
                                 'border: 1.5px solid #EEEEEE; background: #FFFFFF;'"
                         style="border-radius: 12px; padding: 16px 20px; cursor: pointer;
                                transition: all 0.2s ease; display: flex; align-items: center;
                                gap: 14px; user-select: none;
                                box-shadow: 0 1px 4px rgba(0,0,0,0.04);">

                        {{-- Case à cocher carrée --}}
                        <div :style="options.{{ $opt['key'] }} ?
                                     'background: #CC0000; border-color: #CC0000;' :
                                     'background: white; border-color: #DDDDDD;'"
                             style="width: 18px; height: 18px; border: 2px solid #DDDDDD;
                                    border-radius: 4px; display: flex; align-items: center;
                                    justify-content: center; flex-shrink: 0; transition: all 0.2s ease;">
                            <svg x-show="options.{{ $opt['key'] }}" width="10" height="8"
                                 viewBox="0 0 10 8" fill="none">
                                <path d="M1 4L3.5 6.5L9 1" stroke="white" stroke-width="1.8"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        {{-- Texte --}}
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

                        {{-- Prix --}}
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
                               :checked="options.{{ $opt['key'] }}"
                               style="display: none;">
                    </div>

                    @endforeach

                    {{-- Total --}}
                    <div x-show="total > 0" x-transition
                         style="margin-top: 4px; padding: 14px 20px; background: #F9F9F9;
                                border: 1px solid #EEEEEE; border-radius: 10px;
                                display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-family: 'Poppins', sans-serif; font-size: 13px;
                                     color: #666666; font-weight: 500;">
                            Total options premium
                        </span>
                        <span style="font-family: 'Poppins', sans-serif; font-weight: 700;
                                     font-size: 18px; color: #CC0000;"
                              x-text="total.toLocaleString('fr-FR') + ' FCA'">
                        </span>
                    </div>

                </div>
            </div>

            {{-- Badge J'y serai Section --}}
            <div class="form-card" x-data="{
                badgeActif: false,
                affichePreview: null,
                canvasCtx: null,
                imageLoaded: false,
                zoneType: 'cercle',
                zoneX: 50,
                zoneY: 50,
                zoneWidth: 30,
                zoneHeight: 30,
                isDragging: false,
                isResizing: false,
                resizeCorner: null,
                startX: 0,
                startY: 0,
                startZone: {},
                loadAffiche(event) {
                    const file = event.target.files[0];
                    if (file) {
                        if (file.size > 10 * 1024 * 1024) {
                            alert('Image trop grande. Maximum 10MB.');
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.affichePreview = e.target.result;
                            this.$nextTick(() => this.initCanvas());
                        };
                        reader.readAsDataURL(file);
                    }
                },
                initCanvas() {
                    const canvas = document.getElementById('badge-canvas');
                    if (!canvas) return;
                    this.canvasCtx = canvas.getContext('2d');
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload = () => {
                        this.imageLoaded = true;
                        this.drawCanvas(img);
                    };
                    img.src = this.affichePreview;
                },
                drawCanvas(img) {
                    if (!this.canvasCtx || !this.imageLoaded) return;
                    const canvas = this.canvasCtx.canvas;
                    const ctx = this.canvasCtx;

                    const maxWidth = 600;
                    const scale = Math.min(1, maxWidth / img.width);
                    canvas.width = img.width * scale;
                    canvas.height = img.height * scale;

                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                    const x = (this.zoneX / 100) * canvas.width;
                    const y = (this.zoneY / 100) * canvas.height;
                    const w = (this.zoneWidth / 100) * canvas.width;
                    const h = (this.zoneHeight / 100) * canvas.height;

                    ctx.fillStyle = 'rgba(204, 0, 0, 0.35)';
                    ctx.strokeStyle = '#CC0000';
                    ctx.lineWidth = 2;

                    if (this.zoneType === 'cercle') {
                        ctx.beginPath();
                        ctx.arc(x, y, Math.min(w, h) / 2, 0, Math.PI * 2);
                        ctx.fill();
                        ctx.stroke();
                    } else {
                        ctx.fillRect(x - w/2, y - h/2, w, h);
                        ctx.strokeRect(x - w/2, y - h/2, w, h);
                    }

                    if (this.zoneType === 'rectangle') {
                        const handleSize = 8;
                        ctx.fillStyle = '#CC0000';
                        ctx.fillRect(x - w/2 - handleSize/2, y - h/2 - handleSize/2, handleSize, handleSize);
                        ctx.fillRect(x + w/2 - handleSize/2, y - h/2 - handleSize/2, handleSize, handleSize);
                        ctx.fillRect(x - w/2 - handleSize/2, y + h/2 - handleSize/2, handleSize, handleSize);
                        ctx.fillRect(x + w/2 - handleSize/2, y + h/2 - handleSize/2, handleSize, handleSize);
                    }

                    document.getElementById('badge_zone_x').value = this.zoneX;
                    document.getElementById('badge_zone_y').value = this.zoneY;
                    document.getElementById('badge_zone_width').value = this.zoneWidth;
                    document.getElementById('badge_zone_height').value = this.zoneHeight;
                },
                handleMouseDown(e) {
                    const canvas = this.canvasCtx.canvas;
                    const rect = canvas.getBoundingClientRect();
                    const x = (e.clientX - rect.left) * (canvas.width / rect.width);
                    const y = (e.clientY - rect.top) * (canvas.height / rect.height);

                    const zoneX = (this.zoneX / 100) * canvas.width;
                    const zoneY = (this.zoneY / 100) * canvas.height;
                    const zoneW = (this.zoneWidth / 100) * canvas.width;
                    const zoneH = (this.zoneHeight / 100) * canvas.height;

                    if (this.zoneType === 'rectangle') {
                        const handleSize = 12;
                        const corners = [
                            { cx: zoneX - zoneW/2, cy: zoneY - zoneH/2 },
                            { cx: zoneX + zoneW/2, cy: zoneY - zoneH/2 },
                            { cx: zoneX - zoneW/2, cy: zoneY + zoneH/2 },
                            { cx: zoneX + zoneW/2, cy: zoneY + zoneH/2 },
                        ];
                        for (let i = 0; i < corners.length; i++) {
                            if (Math.abs(x - corners[i].cx) < handleSize && Math.abs(y - corners[i].cy) < handleSize) {
                                this.isResizing = true;
                                this.resizeCorner = i;
                                this.startX = x;
                                this.startY = y;
                                this.startZone = { x: this.zoneX, y: this.zoneY, w: this.zoneWidth, h: this.zoneHeight };
                                return;
                            }
                        }
                    }

                    if (this.zoneType === 'cercle') {
                        const radius = Math.min(zoneW, zoneH) / 2;
                        const dist = Math.sqrt((x - zoneX) ** 2 + (y - zoneY) ** 2);
                        if (dist <= radius) {
                            this.isDragging = true;
                        }
                    } else {
                        if (x >= zoneX - zoneW/2 && x <= zoneX + zoneW/2 &&
                            y >= zoneY - zoneH/2 && y <= zoneY + zoneH/2) {
                            this.isDragging = true;
                        }
                    }

                    if (this.isDragging) {
                        this.startX = x;
                        this.startY = y;
                    }
                },
                handleMouseMove(e) {
                    if (!this.isDragging && !this.isResizing) return;

                    const canvas = this.canvasCtx.canvas;
                    const rect = canvas.getBoundingClientRect();
                    const x = (e.clientX - rect.left) * (canvas.width / rect.width);
                    const y = (e.clientY - rect.top) * (canvas.height / rect.height);

                    if (this.isDragging) {
                        const dx = x - this.startX;
                        const dy = y - this.startY;
                        const dxPercent = (dx / canvas.width) * 100;
                        const dyPercent = (dy / canvas.height) * 100;

                        this.zoneX = Math.max(0, Math.min(100, this.startZone.x + dxPercent));
                        this.zoneY = Math.max(0, Math.min(100, this.startZone.y + dyPercent));

                        this.startX = x;
                        this.startY = y;
                    } else if (this.isResizing) {
                        const dx = x - this.startX;
                        const dy = y - this.startY;
                        const dxPercent = (dx / canvas.width) * 100;
                        const dyPercent = (dy / canvas.height) * 100;

                        if (this.resizeCorner === 0) {
                            this.zoneWidth = Math.max(10, this.startZone.w - dxPercent);
                            this.zoneHeight = Math.max(10, this.startZone.h - dyPercent);
                            this.zoneX = this.startZone.x + dxPercent / 2;
                            this.zoneY = this.startZone.y + dyPercent / 2;
                        } else if (this.resizeCorner === 1) {
                            this.zoneWidth = Math.max(10, this.startZone.w + dxPercent);
                            this.zoneHeight = Math.max(10, this.startZone.h - dyPercent);
                            this.zoneY = this.startZone.y + dyPercent / 2;
                        } else if (this.resizeCorner === 2) {
                            this.zoneWidth = Math.max(10, this.startZone.w - dxPercent);
                            this.zoneHeight = Math.max(10, this.startZone.h + dyPercent);
                            this.zoneX = this.startZone.x + dxPercent / 2;
                        } else if (this.resizeCorner === 3) {
                            this.zoneWidth = Math.max(10, this.startZone.w + dxPercent);
                            this.zoneHeight = Math.max(10, this.startZone.h + dyPercent);
                        }

                        this.zoneX = Math.max(0, Math.min(100, this.zoneX));
                        this.zoneY = Math.max(0, Math.min(100, this.zoneY));
                        this.zoneWidth = Math.max(10, Math.min(100, this.zoneWidth));
                        this.zoneHeight = Math.max(10, Math.min(100, this.zoneHeight));
                    }

                    this.redrawCanvas();
                },
                handleMouseUp() {
                    this.isDragging = false;
                    this.isResizing = false;
                    this.resizeCorner = null;
                },
                redrawCanvas() {
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload = () => this.drawCanvas(img);
                    img.src = this.affichePreview;
                },
                updateZoneType(type) {
                    this.zoneType = type;
                    document.getElementById('badge_zone_type').value = type;
                    this.redrawCanvas();
                }
            }">
                <h3 class="card-title">Badge "J'y serai"</h3>

                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <label style="position: relative; display: inline-block; width: 52px; height: 28px; cursor: pointer;">
                        <input type="checkbox" x-model="badgeActif" name="badge_actif" value="1" style="opacity: 0; width: 0; height: 0;">
                        <span style="position: absolute; inset: 0; background: #ddd; border-radius: 28px; transition: 0.3s;"
                              :style="badgeActif ? 'background: #CC0000;' : 'background: #ddd;'"></span>
                        <span style="position: absolute; top: 2px; left: 2px; width: 24px; height: 24px; background: white; border-radius: 50%; transition: 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"
                              :style="badgeActif ? 'transform: translateX(24px);' : ''"></span>
                    </label>
                    <span style="font-family: 'Poppins', sans-serif; font-size: 13px; color: #666;">
                        Activer le badge J'y serai — Permettez à vos participants de créer un visuel personnalisé à partager
                    </span>
                </div>

                <div x-show="badgeActif" x-transition style="margin-top: 20px;">
                    <div class="upload-zone"
                         :class="affichePreview ? 'has-image' : ''"
                         style="border: 2px dashed #CC0000; background: #FFF5F5; border-radius: 12px; height: 180px; display: flex; align-items: center; justify-content: center;">
                        <template x-if="!affichePreview">
                            <div style="text-align: center; color: #CC0000;">
                                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 8px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p style="font-family: 'Poppins', sans-serif; font-size: 13px; margin: 0; font-weight: 600;">
                                    Uploadez l'affiche officielle de votre événement (PNG, JPG, max 10MB)
                                </p>
                            </div>
                        </template>
                        <template x-if="affichePreview">
                            <div style="width: 100%; height: 100%; position: relative;">
                                <img :src="affichePreview" alt="Affiche" style="width: 100%; height: 100%; object-fit: contain; border-radius: 10px;">
                                <button type="button" @click="affichePreview = null; imageLoaded = false;"
                                        style="position: absolute; top: 8px; right: 8px; width: 32px; height: 32px; border-radius: 50%; background: rgba(0,0,0,0.7); color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;">
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
                               style="position: absolute; inset: 0; opacity: 0; cursor: pointer;">
                    </div>

                    <div x-show="imageLoaded" x-transition style="margin-top: 20px;">
                        <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                            <button type="button"
                                    @click="updateZoneType('cercle')"
                                    :style="zoneType === 'cercle' ? 'background: #CC0000; color: white; border-color: #CC0000;' : 'background: white; color: #666; border-color: #ddd;'"
                                    style="padding: 10px 20px; border: 2px solid #ddd; border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                Cercle
                            </button>
                            <button type="button"
                                    @click="updateZoneType('rectangle')"
                                    :style="zoneType === 'rectangle' ? 'background: #CC0000; color: white; border-color: #CC0000;' : 'background: white; color: #666; border-color: #ddd;'"
                                    style="padding: 10px 20px; border: 2px solid #ddd; border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                Rectangle
                            </button>
                        </div>

                        <p style="font-family: 'Poppins', sans-serif; font-size: 12px; color: #888; margin-bottom: 12px;">
                            Placez le cadre à l'endroit où apparaîtra la photo du participant. Faites glisser le cadre pour le déplacer, ou utilisez les coins pour le redimensionner.
                        </p>

                        <canvas id="badge-canvas"
                                @mousedown="handleMouseDown($event)"
                                @mousemove="handleMouseMove($event)"
                                @mouseup="handleMouseUp()"
                                @mouseleave="handleMouseUp()"
                                style="max-width: 100%; border-radius: 8px; cursor: move; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        </canvas>

                        <input type="hidden" id="badge_zone_type" name="badge_zone_type" x-model="zoneType">
                        <input type="hidden" id="badge_zone_x" name="badge_zone_x" x-model="zoneX">
                        <input type="hidden" id="badge_zone_y" name="badge_zone_y" x-model="zoneY">
                        <input type="hidden" id="badge_zone_width" name="badge_zone_width" x-model="zoneWidth">
                        <input type="hidden" id="badge_zone_height" name="badge_zone_height" x-model="zoneHeight">
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