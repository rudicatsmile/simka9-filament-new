<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model JabatanUtama
 * 
 * Model untuk mengelola data jabatan utama dalam sistem
 * 
 * @property int $id
 * @property string $kode
 * @property string $kode_unit_kerja
 * @property string $nama_jabatan_utama
 * @property string $status
 * @property int $urut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\UnitKerja $unitKerja
 */
class JabatanUtama extends Model
{
    /** @use HasFactory<\Database\Factories\JabatanUtamaFactory> */
    use HasFactory;

    /**
     * Nama tabel dalam database
     *
     * @var string
     */
    protected $table = 'jabatan_utama';

    /**
     * Field yang dapat diisi secara mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode',
        'kode_unit_kerja',
        'nama_jabatan_utama',
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

    /**
     * Relasi ke model UnitKerja
     * 
     * Setiap jabatan utama belongs to satu unit kerja
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'kode_unit_kerja', 'kode');
    }
}