<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['nom', 'slug', 'icone', 'ordre'];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre');
    }
}
