<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenjangPendidikan extends Model
{
    /** @use HasFactory<\Database\Factories\JenjangPendidikanFactory> */
    use HasFactory;

    protected $table = 'jenjang_pendidikan';

    protected $fillable = [
        'kode',
        'nama_jenjang_pendidikan',
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