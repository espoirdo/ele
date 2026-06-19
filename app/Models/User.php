<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\MustVerifyEmail;

class User extends Authenticatable
{
    use HasFactory, MustVerifyEmail, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_blocked',
        'avatar',
        'phone',
        'country',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_blocked' => 'boolean',
        'password' => 'hashed',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeBlocked($query)
    {
        return $query->where('is_blocked', true);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function roleModel(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hasPermission(string $slug): bool
    {
        if (!$this->roleModel) {
            return $this->role === 'admin';
        }
        return $this->roleModel->hasPermission($slug);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=C0392B&color=fff';
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->role === 'admin';
    }
}