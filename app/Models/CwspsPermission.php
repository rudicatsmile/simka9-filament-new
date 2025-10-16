<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Model untuk Permission dalam sistem CWSPS
 * 
 * @property string $id
 * @property string $name
 * @property string $identifier
 * @property array $panel_ids
 * @property string $route
 * @property bool $status
 */
class CwspsPermission extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'cwsps_permissions';

    protected $fillable = [
        'name',
        'identifier',
        'panel_ids',
        'route',
        'status',
    ];

    protected $casts = [
        'panel_ids' => 'array',
        'status' => 'boolean',
    ];

    /**
     * Relasi dengan roles melalui pivot table
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            CwspsRole::class,
            'cwsps_role_permissions',
            'permission_id',
            'role_id'
        );
    }

    /**
     * Scope untuk permission yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope untuk permission berdasarkan panel
     */
    public function scopeForPanel($query, string $panelId)
    {
        return $query->whereJsonContains('panel_ids', $panelId);
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