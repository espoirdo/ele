<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'transaction_id',
        // PZGate fields
        'pzgate_reference',
        'pzgate_transaction_id',
        'pzgate_status',
        'pzgate_response',
    ];

    protected $casts = [
        'options' => 'array',
        'total' => 'integer',
        'pzgate_response' => 'array',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
