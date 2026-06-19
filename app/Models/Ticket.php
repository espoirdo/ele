<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = ['event_id', 'nom', 'prix', 'quantite_totale', 'quantite_vendue'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getDisponiblesAttribute(): int
    {
        return $this->quantite_totale - $this->quantite_vendue;
    }
}
