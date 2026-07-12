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
    ];

    protected $casts = [
        'montant' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
};