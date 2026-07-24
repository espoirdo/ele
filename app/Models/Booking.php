<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'ticket_id',
        'total',
        'type_billet',
        'status',
        'numero_reservation',
        'ticket_path',
        // PayGate fields
        'paygate_tx_reference',
        'paygate_identifier',
        'paygate_status',
        'paygate_response',
        'paygate_payment_reference',
        // Moyen de paiement
        'moyen_paiement',
    ];

    protected $casts = [
        'type_billet' => 'string',
        'paygate_response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get ticket type info with name and color
     */
    public function getTypeBilletInfoAttribute(): array
    {
        $types = [
            'classique' => ['nom' => 'Classique', 'couleur' => '#333333'],
            'vip' => ['nom' => 'VIP', 'couleur' => '#CC0000'],
            'vvip' => ['nom' => 'VVIP', 'couleur' => '#F5A623'],
        ];

        return $types[$this->type_billet] ?? ['nom' => 'Standard', 'couleur' => '#666666'];
    }
}
