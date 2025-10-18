<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model untuk mengelola data riwayat unit kerja lain pegawai
 *
 * @property int $id
 * @property string $nik_data_pegawai
 * @property string|null $is_bekerja_di_tempat_lain
 * @property string|null $status
 * @property string|null $nama
 * @property string|null $jabatan
 * @property string|null $fungsi
 * @property int $urut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DataPegawai $pegawai
 */
class DataRiwayatUnitKerjaLain extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_riwayat_unit_kerja_lain';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik_data_pegawai',
        'is_bekerja_di_tempat_lain',
        'status',
        'nama',
        'jabatan',
        'fungsi',
        'urut'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_bekerja_di_tempat_lain' => 'boolean',
        'urut' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Validation rules for the model
     *
     * @return array<string, array>
     */
    public static function getValidationRules(): array
    {
        return [
            'nik_data_pegawai' => ['required', 'string', 'max:50', 'exists:data_pegawai,nik'],
            'is_bekerja_di_tempat_lain' => ['nullable', 'in:1,0'],
            'status' => ['nullable', 'in:aktif,tidak_aktif'],
            'nama' => ['nullable', 'string', 'max:100'],
            'jabatan' => ['nullable', 'string', 'max:50'],
            'fungsi' => ['nullable', 'string', 'max:5'],
            'urut' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Get the pegawai that owns the riwayat unit kerja lain.
     *
     * Relasi ke model DataPegawai berdasarkan NIK
     */
    public function dataPegawai(): BelongsTo
    {
        return $this->belongsTo(DataPegawai::class, 'nik_data_pegawai', 'nik');
    }

    /**
     * Alias for dataPegawai relationship
     */
    public function pegawai(): BelongsTo
    {
        return $this->dataPegawai();
    }

    /**
     * Accessor for unit kerja through pegawai relationship
     */
    public function getUnitKerjaAttribute()
    {
        return $this->pegawai?->unitKerja;
    }

    /**
     * Scope untuk filter berdasarkan NIK pegawai
     */
    public function scopeByPegawai($query, string $nik)
    {
        return $query->where('nik_data_pegawai', $nik);
    }

    /**
     * Scope untuk filter berdasarkan unit kerja
     */
    public function scopeByUnitKerja($query, string $kodeUnitKerja)
    {
        return $query->whereHas('pegawai', function ($q) use ($kodeUnitKerja) {
            $q->where('kode_unit_kerja', $kodeUnitKerja);
        });
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan status bekerja di tempat lain
     */
    public function scopeByBekerjaLain($query, string $status)
    {
        return $query->where('is_bekerja_di_tempat_lain', $status);
    }

    /**
     * Scope untuk mengurutkan berdasarkan urutan prioritas
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urut', 'asc');
    }

    /**
     * Get the display name for the riwayat unit kerja lain
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->nama ?? 'Tidak ada nama instansi';
    }

    /**
     * Get the full description for the riwayat unit kerja lain
     */
    public function getFullDescriptionAttribute(): string
    {
        $pegawaiNama = $this->pegawai?->nama_lengkap ?? 'Unknown';
        $instansi = $this->nama ?? 'Unknown';
        $jabatan = $this->jabatan ?? 'Unknown';

        return "{$pegawaiNama} - {$instansi} - {$jabatan}";
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Tetap' => 'success',
            'Tidak Tetap' => 'warning',
            'ASN' => 'primary',
            default => 'secondary'
        };
    }

    /**
     * Get bekerja lain badge color
     */
    public function getBekerjaLainColorAttribute(): string
    {
        return match ($this->is_bekerja_di_tempat_lain) {
            'Ya' => 'success',
            'Tidak' => 'secondary',
            default => 'secondary'
        };
    }
}
