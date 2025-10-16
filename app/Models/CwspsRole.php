<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model untuk Role dalam sistem CWSPS
 * 
 * @property string $id
 * @property string $name
 * @property string $identifier
 * @property array $panel_ids
 * @property bool $all_permission
 * @property bool $status
 */
class CwspsRole extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'cwsps_roles';

    protected $fillable = [
        'name',
        'identifier',
        'panel_ids',
        'all_permission',
        'status',
    ];

    protected $casts = [
        'panel_ids' => 'array',
        'all_permission' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Relasi dengan permissions melalui pivot table
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            CwspsPermission::class,
            'cwsps_role_permissions',
            'role_id',
            'permission_id'
        );
    }

    /**
     * Relasi dengan users yang memiliki role ini
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * Cek apakah role memiliki permission tertentu
     */
    public function hasPermission(string $identifier): bool
    {
        if ($this->all_permission) {
            return true;
        }

        return $this->permissions()
            ->where('identifier', $identifier)
            ->where('status', true)
            ->exists();
    }

    /**
     * Assign permission ke role
     */
    public function assignPermission(CwspsPermission $permission): void
    {
        $this->permissions()->syncWithoutDetaching([$permission->id]);
    }

    /**
     * Remove permission dari role
     */
    public function removePermission(CwspsPermission $permission): void
    {
        $this->permissions()->detach($permission->id);
    }
    public function setStatusAttribute($value): void
    {
        if (is_string($value)) {
            $value = strtolower($value);
            $this->attributes['status'] = in_array($value, ['active', 'true', '1', 'on', 'yes']);
            return;
        }
        $this->attributes['status'] = (bool) $value;
    }
}