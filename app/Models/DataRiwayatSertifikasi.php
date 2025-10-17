<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * Model DataRiwayatSertifikasi
 * 
 * Model untuk mengelola data riwayat sertifikasi pegawai dalam sistem
 * 
 * @property int $id
 * @property string $nik_data_pegawai
 * @property string|null $is_sertifikasi
 * @property string|null $nama
 * @property string|null $nomor
 * @property string|null $tahun
 * @property string|null $induk_inpasing
 * @property string|null $sk_inpasing
 * @property string|null $tahun_inpasing
 * @property string|null $berkas
 * @property int $urut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @package App\Models
 * @author Laravel Filament
 * @version 1.0.0
 */
class DataRiwayatSertifikasi extends Model
{
    /** @use HasFactory<\Database\Factories\DataRiwayatSertifikasiFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_riwayat_sertifikasi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik_data_pegawai',
        'is_sertifikasi',
        'nama',
        'nomor',
        'tahun',
        'induk_inpasing',
        'sk_inpasing',
        'tahun_inpasing',
        'berkas',
        'urut',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'urut' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The possible values for is_sertifikasi field.
     *
     * @var array<string>
     */
    public const IS_SERTIFIKASI_OPTIONS = [
        'Ya',
        'Tidak',
    ];

    /**
     * Relasi ke data pegawai
     *
     * @return BelongsTo
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(DataPegawai::class, 'nik_data_pegawai', 'nik');
    }

    /**
     * Scope untuk sertifikasi yang aktif
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_sertifikasi', 'Ya');
    }

    /**
     * Scope untuk filter berdasarkan tahun
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $tahun
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Scope untuk filter berdasarkan pegawai
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $nik
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPegawai($query, $nik)
    {
        return $query->where('nik_data_pegawai', $nik);
    }

    /**
     * Get the full path of the berkas file
     *
     * @return string|null
     */
    public function getBerkasPathAttribute(): ?string
    {
        if (!$this->berkas) {
            return null;
        }

        return Storage::disk('public')->path($this->berkas);
    }

    /**
     * Get the URL of the berkas file
     *
     * @return string|null
     */
    public function getBerkasUrlAttribute(): ?string
    {
        if (!$this->berkas) {
            return null;
        }

        return Storage::disk('public')->url($this->berkas);
    }

    /**
     * Check if berkas file exists
     *
     * @return bool
     */
    public function hasBerkas(): bool
    {
        return $this->berkas && Storage::disk('public')->exists($this->berkas);
    }

    /**
     * Get sertifikasi status label
     *
     * @return string
     */
    public function getSertifikasiStatusLabelAttribute(): string
    {
        return $this->is_sertifikasi === 'Ya' ? 'Tersertifikasi' : 'Belum Tersertifikasi';
    }

    /**
     * Get formatted display name with year
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        $name = $this->nama ?? 'Sertifikasi';
        $year = $this->tahun ? " ({$this->tahun})" : '';
        
        return $name . $year;
    }

    /**
     * Delete berkas file when model is deleted
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            if ($model->berkas && Storage::disk('public')->exists($model->berkas)) {
                Storage::disk('public')->delete($model->berkas);
            }
        });
    }
}