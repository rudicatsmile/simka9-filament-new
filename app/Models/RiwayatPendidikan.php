<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model untuk mengelola data riwayat pendidikan pegawai
 * 
 * @property int $id
 * @property string $nik_data_pegawai
 * @property string $kode_jenjang_pendidikan
 * @property string $nama_sekolah
 * @property string $tahun_ijazah
 * @property int $urut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DataPegawai $pegawai
 * @property-read \App\Models\JenjangPendidikan $jenjangPendidikan
 */
class RiwayatPendidikan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_riwayat_pendidikan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik_data_pegawai',
        'kode_jenjang_pendidikan',
        'nama_sekolah',
        'tahun_ijazah',
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
     * Get the pegawai that owns the riwayat pendidikan.
     * 
     * Relasi ke model DataPegawai berdasarkan NIK
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(DataPegawai::class, 'nik_data_pegawai', 'nik');
    }

    /**
     * Get the jenjang pendidikan that categorizes the riwayat pendidikan.
     * 
     * Relasi ke model JenjangPendidikan berdasarkan kode
     */
    public function jenjangPendidikan(): BelongsTo
    {
        return $this->belongsTo(JenjangPendidikan::class, 'kode_jenjang_pendidikan', 'kode');
    }

    /**
     * Scope untuk filter berdasarkan NIK pegawai
     */
    public function scopeByPegawai($query, string $nik)
    {
        return $query->where('nik_data_pegawai', $nik);
    }

    /**
     * Scope untuk filter berdasarkan jenjang pendidikan
     */
    public function scopeByJenjang($query, string $kode)
    {
        return $query->where('kode_jenjang_pendidikan', $kode);
    }

    /**
     * Scope untuk filter berdasarkan tahun ijazah
     */
    public function scopeByTahun($query, string $tahun)
    {
        return $query->where('tahun_ijazah', $tahun);
    }

    /**
     * Scope untuk mengurutkan berdasarkan urutan prioritas
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urut', 'asc');
    }

    /**
     * Get the display name for the riwayat pendidikan
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->nama_sekolah} ({$this->tahun_ijazah})";
    }

    /**
     * Get the full description for the riwayat pendidikan
     */
    public function getFullDescriptionAttribute(): string
    {
        $pegawaiNama = $this->pegawai?->nama_lengkap ?? 'Unknown';
        $jenjangNama = $this->jenjangPendidikan?->nama_jenjang_pendidikan ?? 'Unknown';
        
        return "{$pegawaiNama} - {$jenjangNama} - {$this->nama_sekolah} ({$this->tahun_ijazah})";
    }
}