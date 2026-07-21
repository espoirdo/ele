<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PremiumPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'options',
        'total',
        'moyen_paiement',
        'statut',
    ];

    protected $casts = [
        'options' => 'array',
        'total' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
