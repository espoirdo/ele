<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'titre', 'description', 'image_couverture',
        'date', 'date_fin', 'heure_debut', 'heure_fin', 'lieu', 'latitude', 'longitude', 'statut',
        'premium_mise_en_avant', 'premium_newsletter', 'premium_reseaux',
        'est_gratuit', 'raison_rejet',
        // Ticket types
        'billet_classique_actif', 'billet_classique_prix',
        'billet_vip_actif', 'billet_vip_prix',
        'billet_vvip_actif', 'billet_vvip_prix',
        // Badge "J'y serai"
        'affiche_officielle', 'badge_zone_type', 'badge_zone_x', 'badge_zone_y',
        'badge_zone_width', 'badge_zone_height', 'badge_actif', 'badge_valide_admin',
        'badge_nb_generations',
    ];

    protected $casts = [
        'date' => 'date',
        'date_fin' => 'date',
        'est_gratuit' => 'boolean',
        'premium_mise_en_avant' => 'boolean',
        'premium_newsletter' => 'boolean',
        'premium_reseaux' => 'boolean',
        // Ticket types casts
        'billet_classique_actif' => 'boolean',
        'billet_classique_prix' => 'decimal:2',
        'billet_vip_actif' => 'boolean',
        'billet_vip_prix' => 'decimal:2',
        'billet_vvip_actif' => 'boolean',
        'billet_vvip_prix' => 'decimal:2',
        // Badge casts
        'badge_actif' => 'boolean',
        'badge_valide_admin' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('approuve', true);
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopePublie($query)
    {
        return $query->where('statut', 'publie');
    }

    public function scopePremium($query)
    {
        return $query->where('premium_mise_en_avant', true);
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image_couverture) {
            return Storage::url($this->image_couverture);
        }
        // Utiliser l'image par defaut configuree dans les parametres
        $defaultImage = setting('image_evenement_defaut');
        if ($defaultImage) {
            return Storage::url($defaultImage);
        }
        return 'https://picsum.photos/seed/' . $this->id . '/800/450';
    }

    /**
     * Get the official poster URL for badge
     */
    public function getAfficheOfficielleUrlAttribute(): ?string
    {
        if ($this->affiche_officielle && Storage::disk('public')->exists($this->affiche_officielle)) {
            return route('events.affiche', $this);
        }
        return null;
    }

    /**
     * Get badge zone coordinates as percentages
     */
    public function getBadgeZonePercentAttribute(): ?array
    {
        if (!$this->badge_actif || !$this->badge_zone_x) {
            return null;
        }
        return [
            'x' => $this->badge_zone_x,
            'y' => $this->badge_zone_y,
            'width' => $this->badge_zone_width,
            'height' => $this->badge_zone_height,
            'type' => $this->badge_zone_type ?? 'cercle',
        ];
    }

    public function getNoteMoyenneAttribute(): float
    {
        return $this->comments()->avg('note') ?? 0;
    }

    public function getDateHeureAttribute()
    {
        if (! $this->date) {
            return null;
        }

        $time = $this->heure_debut ?: '00:00:00';
        return Carbon::parse(sprintf('%s %s', $this->date->format('Y-m-d'), $time));
    }

    /**
     * Get active ticket types with their prices
     */
    public function getTicketsActifsAttribute(): array
    {
        $tickets = [];

        $types = [
            'classique' => ['nom' => 'Classique', 'couleur' => '#333333'],
            'vip' => ['nom' => 'VIP', 'couleur' => '#CC0000'],
            'vvip' => ['nom' => 'VVIP', 'couleur' => '#F5A623'],
        ];

        foreach ($types as $key => $type) {
            $actif = $this->{"billet_{$key}_actif"};
            $prix = $this->{"billet_{$key}_prix"};

            if ($actif) {
                $tickets[] = [
                    'type' => $key,
                    'nom' => $type['nom'],
                    'couleur' => $type['couleur'],
                    'prix' => $prix ?? 0,
                    'est_gratuit' => $prix == 0 || $prix === null,
                ];
            }
        }

        return $tickets;
    }

    public function getSlugAttribute(): string
    {
        return Str::slug($this->titre);
    }

    public function getRouteKey()
    {
        return $this->slug;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->slug = Str::slug($model->titre);
        });

        static::updating(function ($model) {
            if ($model->isDirty('titre')) {
                $model->slug = Str::slug($model->titre);
            }
        });
    }
}
