<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = ['nom', 'slug'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function hasPermission(string $slug): bool
    {
        // Administrateur total a toutes les permissions
        if ($this->slug === 'admin_total') {
            return true;
        }

        return $this->permissions()->where('slug', $slug)->exists();
    }
}