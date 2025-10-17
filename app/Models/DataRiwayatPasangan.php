<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model untuk mengelola data riwayat pasangan pegawai
 * 
 * @property int $id
 * @property string $nik_data_pegawai
 * @property string|null $nama_pasangan
 * @property string|null $tempat_lahir
 * @property \Illuminate\Support\Carbon|null $tanggal_lahir
 * @property string $hubungan
 * @property string|null $kode_jenjang_pendidikan
 * @property string|null $kode_tabel_pekerjaan
 * @property int $urut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DataPegawai $pegawai
 * @property-read \App\Models\JenjangPendidikan|null $jenjangPendidikan
 * @property-read \App\Models\TabelPekerjaan|null $pekerjaan
 */
class DataRiwayatPasangan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_riwayat_pasangan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik_data_pegawai',
        'nama_pasangan',
        'tempat_lahir',
        'tanggal_lahir',
        'hubungan',
        'kode_jenjang_pendidikan',
        'kode_tabel_pekerjaan',
        'urut',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'urut' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The possible values for hubungan field.
     *
     * @var array<string>
     */
    public const HUBUNGAN_OPTIONS = [
        'Suami',
        'Istri',
    ];

    /**
     * Get the pegawai that owns the data riwayat pasangan.
     * 
     * Relasi ke model DataPegawai berdasarkan NIK
     */
    public function dataPegawai(): BelongsTo
    {
        return $this->belongsTo(DataPegawai::class, 'nik_data_pegawai', 'nik');
    }

    /**
     * Alias for dataPegawai relationship for backward compatibility
     */
    public function pegawai(): BelongsTo
    {
        return $this->dataPegawai();
    }

    /**
     * Get the jenjang pendidikan that categorizes the pasangan's education.
     * 
     * Relasi ke model JenjangPendidikan berdasarkan kode
     */
    public function jenjangPendidikan(): BelongsTo
    {
        return $this->belongsTo(JenjangPendidikan::class, 'kode_jenjang_pendidikan', 'kode');
    }

    /**
     * Get the pekerjaan that categorizes the pasangan's occupation.
     * 
     * Relasi ke model TabelPekerjaan berdasarkan kode
     */
    public function tabelPekerjaan(): BelongsTo
    {
        return $this->belongsTo(TabelPekerjaan::class, 'kode_tabel_pekerjaan', 'kode');
    }

    /**
     * Alias for tabelPekerjaan relationship for backward compatibility
     */
    public function pekerjaan(): BelongsTo
    {
        return $this->tabelPekerjaan();
    }

    /**
     * Scope untuk filter berdasarkan NIK pegawai
     */
    public function scopeByEmployee($query, string $nik)
    {
        return $query->where('nik_data_pegawai', $nik);
    }

    /**
     * Alias for byEmployee scope for backward compatibility
     */
    public function scopeByPegawai($query, string $nik)
    {
        return $this->scopeByEmployee($query, $nik);
    }

    /**
     * Scope untuk filter berdasarkan hubungan
     */
    public function scopeByRelation($query, string $hubungan)
    {
        return $query->where('hubungan', $hubungan);
    }

    /**
     * Alias for byRelation scope for backward compatibility
     */
    public function scopeByHubungan($query, string $hubungan)
    {
        return $this->scopeByRelation($query, $hubungan);
    }

    /**
     * Scope untuk filter berdasarkan jenjang pendidikan
     */
    public function scopeByEducationLevel($query, string $kode)
    {
        return $query->where('kode_jenjang_pendidikan', $kode);
    }

    /**
     * Alias for byEducationLevel scope for backward compatibility
     */
    public function scopeByJenjang($query, string $kode)
    {
        return $this->scopeByEducationLevel($query, $kode);
    }

    /**
     * Scope untuk filter berdasarkan pekerjaan
     */
    public function scopeByJob($query, string $kode)
    {
        return $query->where('kode_tabel_pekerjaan', $kode);
    }

    /**
     * Alias for byJob scope for backward compatibility
     */
    public function scopeByPekerjaan($query, string $kode)
    {
        return $this->scopeByJob($query, $kode);
    }

    /**
     * Scope untuk mengurutkan berdasarkan urutan prioritas
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urut', 'asc');
    }

    /**
     * Get the display name for the data riwayat pasangan
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->nama_pasangan ?? 'Tidak ada nama';
    }

    /**
     * Get the full description for the data riwayat pasangan
     */
    public function getFullDescriptionAttribute(): string
    {
        $pegawaiNama = $this->pegawai?->nama_lengkap ?? 'Unknown';
        $namaPasangan = $this->nama_pasangan ?? 'Tidak ada nama';
        
        return "{$pegawaiNama} - {$this->hubungan}: {$namaPasangan}";
    }

    /**
     * Get the age of the pasangan
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->tanggal_lahir) {
            return null;
        }

        return $this->tanggal_lahir->age;
    }

    /**
     * Get the formatted birth place and date
     */
    public function getBirthInfoAttribute(): string
    {
        $parts = [];
        
        if ($this->tempat_lahir) {
            $parts[] = $this->tempat_lahir;
        }
        
        if ($this->tanggal_lahir) {
            $parts[] = $this->tanggal_lahir->format('d/m/Y');
        }
        
        return implode(', ', $parts) ?: 'Tidak ada data';
    }
}