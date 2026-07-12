<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MarketplaceListing extends Model
{
    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'prix',
        'image',
        'statut',
    ];

    protected $casts = [
        'prix' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        return '';
    }

    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }
};