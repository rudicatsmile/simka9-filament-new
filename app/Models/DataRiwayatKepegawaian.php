<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * DataRiwayatKepegawaian Model
 * 
 * Model untuk mengelola data riwayat kepegawaian pegawai
 * 
 * @property int $id
 * @property string $nik_data_pegawai
 * @property string|null $nama
 * @property string|null $tanggal_lahir
 * @property string|null $nomor
 * @property string|null $keterangan
 * @property string|null $berkas
 * @property int $urut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read DataPegawai $pegawai
 */
class DataRiwayatKepegawaian extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_riwayat_kepegawaian';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik_data_pegawai',
        'nama',
        'tanggal_lahir',
        'nomor',
        'keterangan',
        'berkas',
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
    ];

    /**
     * Get the pegawai that owns the riwayat kepegawaian.
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(DataPegawai::class, 'nik_data_pegawai', 'nik');
    }

    /**
     * Get the formatted tanggal lahir.
     *
     * @return string|null
     */
    public function getFormattedTanggalLahirAttribute(): ?string
    {
        return $this->tanggal_lahir?->format('d/m/Y');
    }

    /**
     * Get the formatted date (alias for formatted_tanggal_lahir).
     *
     * @return string|null
     */
    public function getFormattedDateAttribute(): ?string
    {
        return $this->tanggal_lahir?->locale('id')->isoFormat('DD MMMM YYYY');
    }

    /**
     * Get the file URL.
     *
     * @return string|null
     */
    public function getFileUrlAttribute(): ?string
    {
        if (!$this->berkas) {
            return null;
        }

        return Storage::url('kepegawaian/' . $this->berkas);
    }

    /**
     * Get the berkas URL (alias for file_url).
     *
     * @return string|null
     */
    public function getBerkasUrlAttribute(): ?string
    {
        return $this->file_url;
    }

    /**
     * Get the berkas path.
     *
     * @return string|null
     */
    public function getBerkasPathAttribute(): ?string
    {
        if (!$this->berkas) {
            return null;
        }

        return storage_path('app/public/kepegawaian/' . $this->berkas);
    }

    /**
     * Scope a query to only include records for a specific employee.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $nik
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByEmployee($query, string $nik)
    {
        return $query->where('nik_data_pegawai', $nik);
    }

    /**
     * Scope a query to only include records for employees in a specific unit kerja.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $unitKerja
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUnitKerja($query, string $unitKerja)
    {
        return $query->whereHas('pegawai', function ($q) use ($unitKerja) {
            $q->where('kode_unit_kerja', $unitKerja);
        });
    }

    /**
     * Scope a query to only include records within a date range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDateRange($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('tanggal_lahir', [$startDate, $endDate]);
    }

    /**
     * Scope a query to order by urut.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query, string $direction = 'asc')
    {
        return $query->orderBy('urut', $direction);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Delete berkas file when model is deleted
        static::deleting(function ($model) {
            if ($model->berkas && Storage::exists('public/kepegawaian/' . $model->berkas)) {
                Storage::delete('public/kepegawaian/' . $model->berkas);
            }
        });
    }
}