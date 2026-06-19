<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PremiumOption extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'tarif',
    ];

    protected $casts = [
        'tarif' => 'decimal:2',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_premium_option');
    }
}
