<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    /** @use HasFactory<\Database\Factories\AgamaFactory> */
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama_agama',
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
