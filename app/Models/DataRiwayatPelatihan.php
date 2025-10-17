<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model untuk mengelola data riwayat pelatihan pegawai
 * 
 * @property int $id
 * @property string $nik_data_pegawai
 * @property string|null $nama
 * @property string $kode_tabel_jenis_pelatihan
 * @property string|null $penyelenggara
 * @property string|null $angkatan
 * @property string|null $nomor
 * @property \Illuminate\Support\Carbon|null $tanggal
 * @property \Illuminate\Support\Carbon|null $tanggal_sertifikat
 * @property string|null $berkas
 * @property int $urut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DataPegawai $pegawai
 * @property-read \App\Models\TabelJenisPelatihan $jenisPelatihan
 */
class DataRiwayatPelatihan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_riwayat_pelatihan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik_data_pegawai',
        'nama',
        'kode_tabel_jenis_pelatihan',
        'penyelenggara',
        'angkatan',
        'nomor',
        'tanggal',
        'tanggal_sertifikat',
        'berkas',
        'urut',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
        'tanggal_sertifikat' => 'date',
        'urut' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the pegawai that owns the riwayat pelatihan.
     * 
     * Relasi ke model DataPegawai berdasarkan NIK
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(DataPegawai::class, 'nik_data_pegawai', 'nik');
    }

    /**
     * Get the jenis pelatihan that categorizes the riwayat pelatihan.
     * 
     * Relasi ke model TabelJenisPelatihan berdasarkan kode
     */
    public function jenisPelatihan(): BelongsTo
    {
        return $this->belongsTo(TabelJenisPelatihan::class, 'kode_tabel_jenis_pelatihan', 'kode');
    }

    /**
     * Scope untuk filter berdasarkan NIK pegawai
     */
    public function scopeByPegawai($query, string $nik)
    {
        return $query->where('nik_data_pegawai', $nik);
    }

    /**
     * Scope untuk filter berdasarkan employee (alias untuk byPegawai)
     */
    public function scopeByEmployee($query, string $nik)
    {
        return $query->byPegawai($nik);
    }

    /**
     * Scope untuk filter berdasarkan jenis pelatihan
     */
    public function scopeByJenisPelatihan($query, string $kode)
    {
        return $query->where('kode_tabel_jenis_pelatihan', $kode);
    }

    /**
     * Scope untuk filter berdasarkan tanggal pelatihan
     */
    public function scopeByTanggal($query, $tanggal)
    {
        return $query->where('tanggal', $tanggal);
    }

    /**
     * Scope untuk filter berdasarkan penyelenggara
     */
    public function scopeByPenyelenggara($query, string $penyelenggara)
    {
        return $query->where('penyelenggara', 'like', "%{$penyelenggara}%");
    }

    /**
     * Scope untuk mengurutkan berdasarkan urutan prioritas
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urut', 'asc');
    }

    /**
     * Scope untuk filter berdasarkan rentang tanggal
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeByYear($query, int $year)
    {
        return $query->whereYear('tanggal', $year);
    }

    /**
     * Get the display name for the riwayat pelatihan
     */
    public function getDisplayNameAttribute(): string
    {
        $nama = $this->nama ?? $this->jenisPelatihan?->nama_jenis_pelatihan ?? 'Unknown';
        $penyelenggara = $this->penyelenggara ? " - {$this->penyelenggara}" : '';
        
        return "{$nama}{$penyelenggara}";
    }

    /**
     * Get the full description for the riwayat pelatihan
     */
    public function getFullDescriptionAttribute(): string
    {
        $pegawaiNama = $this->pegawai?->nama_lengkap ?? 'Unknown';
        $pelatihanNama = $this->nama ?? $this->jenisPelatihan?->nama_jenis_pelatihan ?? 'Unknown';
        $penyelenggara = $this->penyelenggara ?? 'Unknown';
        $tanggal = $this->tanggal ? $this->tanggal->format('Y-m-d') : 'Unknown';
        
        return "{$pegawaiNama} - {$pelatihanNama} - {$penyelenggara} ({$tanggal})";
    }

    /**
     * Get the certificate file URL
     */
    public function getCertificateUrlAttribute(): ?string
    {
        if (!$this->berkas) {
            return null;
        }

        return asset('storage/' . $this->berkas);
    }

    /**
     * Check if training has certificate file
     */
    public function hasCertificate(): bool
    {
        return !empty($this->berkas);
    }

    /**
     * Get the file extension of the certificate
     */
    public function getCertificateExtensionAttribute(): ?string
    {
        if (!$this->berkas) {
            return null;
        }

        return pathinfo($this->berkas, PATHINFO_EXTENSION);
    }

    /**
     * Check if certificate is an image
     */
    public function isCertificateImage(): bool
    {
        if (!$this->berkas) {
            return false;
        }

        $extension = strtolower($this->certificate_extension);
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    /**
     * Check if certificate is a PDF
     */
    public function isCertificatePdf(): bool
    {
        if (!$this->berkas) {
            return false;
        }

        return strtolower($this->certificate_extension) === 'pdf';
    }
}