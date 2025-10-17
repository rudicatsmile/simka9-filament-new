<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Carbon\Carbon;

/**
 * DataRiwayatAnak Model
 * 
 * Model untuk mengelola data riwayat anak pegawai
 * Menyimpan informasi lengkap tentang anak-anak pegawai
 * 
 * @package App\Models
 * @author SIMKA9 Development Team
 * @version 1.0.0
 * 
 * @property int $id
 * @property string $nik_data_pegawai
 * @property string $nama
 * @property string $jenis_kelamin
 * @property string $tempat_lahir
 * @property \Carbon\Carbon $tanggal_lahir
 * @property int $id_tabel_hubungan_keluarga
 * @property int|null $id_jenjang_pendidikan
 * @property int|null $id_tabel_pekerjaan
 * @property string|null $keterangan
 * @property int $urut
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at

 * 
 * @property-read DataPegawai $pegawai
 * @property-read TabelHubunganKeluarga $hubunganKeluarga
 * @property-read JenjangPendidikan|null $jenjangPendidikan
 * @property-read TabelPekerjaan|null $pekerjaan
 * @property-read string $formatted_tanggal_lahir
 * @property-read int $umur
 * @property-read string $jenis_kelamin_label
 */
class DataRiwayatAnak extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_riwayat_anak';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik_data_pegawai',
        'nama_anak',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'kode_tabel_hubungan_keluarga',
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'formatted_tanggal_lahir',
        'umur',
        'jenis_kelamin_label',
    ];

    /**
     * Relationship: Belongs to DataPegawai
     *
     * @return BelongsTo
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(DataPegawai::class, 'nik_data_pegawai', 'nik');
    }

    /**
     * Relationship: Belongs to TabelHubunganKeluarga
     *
     * @return BelongsTo
     */
    public function hubunganKeluarga(): BelongsTo
    {
        return $this->belongsTo(TabelHubunganKeluarga::class, 'kode_tabel_hubungan_keluarga', 'kode');
    }

    /**
     * Relationship: Belongs to JenjangPendidikan
     *
     * @return BelongsTo
     */
    public function jenjangPendidikan(): BelongsTo
    {
        return $this->belongsTo(JenjangPendidikan::class, 'kode_jenjang_pendidikan', 'kode');
    }

    /**
     * Relationship: Belongs to TabelPekerjaan
     *
     * @return BelongsTo
     */
    public function pekerjaan(): BelongsTo
    {
        return $this->belongsTo(TabelPekerjaan::class, 'kode_tabel_pekerjaan', 'kode');
    }

    /**
     * Accessor: Get formatted birth date in Indonesian
     *
     * @return string|null
     */
    public function getFormattedBirthDateAttribute(): ?string
    {
        return $this->tanggal_lahir ? 
            $this->tanggal_lahir->locale('id')->isoFormat('DD MMMM YYYY') : null;
    }

    /**
     * Accessor: Get age in years
     *
     * @return int|null
     */
    public function getAgeAttribute(): ?int
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }

    /**
     * Accessor: Get gender label in Indonesian
     *
     * @return string
     */
    public function getGenderLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Scope: Filter by employee
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
     * Scope: Filter by unit kerja
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
     * Scope: Filter by age range
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $minAge
     * @param int $maxAge
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByAgeRange($query, int $minAge, int $maxAge)
    {
        $maxDate = Carbon::now()->subYears($minAge);
        $minDate = Carbon::now()->subYears($maxAge + 1);
        
        return $query->whereBetween('tanggal_lahir', [$minDate, $maxDate]);
    }

    /**
     * Scope: Filter by jenis kelamin
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $jenisKelamin
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByJenisKelamin($query, string $jenisKelamin)
    {
        return $query->where('jenis_kelamin', $jenisKelamin);
    }

    /**
     * Scope: Filter by hubungan keluarga
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $hubunganKeluargaKode
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByHubunganKeluarga($query, string $hubunganKeluargaKode)
    {
        return $query->where('kode_tabel_hubungan_keluarga', $hubunganKeluargaKode);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-set urut when creating
        static::creating(function ($model) {
            if (!$model->urut) {
                $maxUrut = static::where('nik_data_pegawai', $model->nik_data_pegawai)->max('urut');
                $model->urut = ($maxUrut ?? 0) + 1;
            }
        });
    }
}