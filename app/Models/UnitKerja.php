<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    /** @use HasFactory<\Database\Factories\UnitKerjaFactory> */
    use HasFactory;

    protected $table = 'unit_kerja';

    protected $fillable = [
        'kode',
        'nama_unit_kerja',
        'status',
        'urut',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Set the status attribute.
     * Ensure status is always stored as string for ENUM compatibility
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = (string) $value;
    }
}