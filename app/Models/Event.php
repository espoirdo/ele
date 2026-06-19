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
        'date', 'heure', 'lieu', 'latitude', 'longitude', 'statut',
        'premium_mise_en_avant', 'premium_newsletter', 'premium_reseaux',
        'est_gratuit', 'nb_places', 'raison_rejet'
    ];

    protected $casts = [
        'date' => 'date',
        'est_gratuit' => 'boolean',
        'premium_mise_en_avant' => 'boolean',
        'premium_newsletter' => 'boolean',
        'premium_reseaux' => 'boolean',
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
        return 'https://picsum.photos/seed/' . $this->id . '/800/450';
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

        $time = $this->heure ?: '00:00:00';
        return Carbon::parse(sprintf('%s %s', $this->date->format('Y-m-d'), $time));
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
