<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'role_id',
        'last_seen',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relasi dengan role
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(CwspsRole::class, 'role_id');
    }

    /**
     * Cek apakah user memiliki permission tertentu
     */
    public function hasPermission(string $identifier): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->hasPermission($identifier);
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->all_permission;
    }

    /**
     * Assign role ke user
     */
    public function assignRole(CwspsRole $role): void
    {
        $this->update(['role_id' => $role->id]);
    }
}
