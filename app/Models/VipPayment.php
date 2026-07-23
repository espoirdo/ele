<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VipPayment extends Model
{
    protected $fillable = [
        'user_id',
        'montant',
        'methode',
        'statut',
        'transaction_id',
        // PZGate fields
        'pzgate_reference',
        'pzgate_transaction_id',
        'pzgate_status',
        'pzgate_response',
    ];

    protected $casts = [
        'montant' => 'integer',
        'pzgate_response' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
