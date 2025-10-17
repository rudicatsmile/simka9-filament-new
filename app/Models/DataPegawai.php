<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

/**
 * Model DataPegawai
 * 
 * Model untuk mengelola data pegawai dalam sistem
 * 
 * @property int $id
 * @property string $nip
 * @property string|null $nik
 * @property int|null $kode_unit_kerja
 * @property string|null $bpjs
 * @property string|null $npwp
 * @property string|null $nuptk
 * @property string $nama_lengkap
 * @property string $password
 * @property string|null $foto
 * @property string|null $tmp_lahir
 * @property \Illuminate\Support\Carbon|null $tgl_lahir
 * @property string $jns_kelamin
 * @property string $kode_agama
 * @property string|null $kode_golongan_darah
 * @property string|null $kode_status_nikah
 * @property string $pstatus
 * @property string $kode_status_kepegawaian
 * @property string $blokir
 * @property string|null $alamat
 * @property string|null $kode_propinsi
 * @property int|null $kodepos
 * @property string|null $alamat2
 * @property string|null $kode_propinsi2
 * @property int|null $kodepos2
 * @property string|null $email
 * @property string|null $no_tlp
 * @property string|null $hobi
 * @property int|null $tinggi_badan
 * @property int|null $berat_badan
 * @property string|null $kode_jabatan_utama
 * @property string|null $unit_fungsi
 * @property string|null $unit_tugas
 * @property string|null $unit_pelajaran
 * @property string|null $mulai_bekerja
 * @property string|null $kode_jenjang_pendidikan
 * @property string|null $program_studi
 * @property string|null $nama_kampus
 * @property string|null $tahun_lulus
 * @property int $login_attempts
 * @property \Illuminate\Support\Carbon|null $last_attempt
 * @property \Illuminate\Support\Carbon|null $blocked_until
 * @property string|null $failed_ip
 * @property \Illuminate\Support\Carbon|null $createdon
 * @property string|null $createdby
 * @property \Illuminate\Support\Carbon|null $updatedon
 * @property string|null $updatedby
 * 
 * @package App\Models
 * @author Laravel Filament
 * @version 1.0.0
 */
class DataPegawai extends Model
{
    /** @use HasFactory<\Database\Factories\DataPegawaiFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_pegawai';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nip',
        'nik',
        'kode_unit_kerja',
        'bpjs',
        'npwp',
        'nuptk',
        'nama_lengkap',
        'password',
        'foto',
        'tmp_lahir',
        'tgl_lahir',
        'jns_kelamin',
        'kode_agama',
        'kode_golongan_darah',
        'kode_status_nikah',
        'pstatus',
        'kode_status_kepegawaian',
        'blokir',
        'alamat',
        'kode_propinsi',
        'kodepos',
        'alamat2',
        'kode_propinsi2',
        'kodepos2',
        'email',
        'no_tlp',
        'hobi',
        'tinggi_badan',
        'berat_badan',
        'kode_jabatan_utama',
        'unit_fungsi',
        'unit_tugas',
        'unit_pelajaran',
        'mulai_bekerja',
        'kode_jenjang_pendidikan',
        'program_studi',
        'nama_kampus',
        'tahun_lulus',
        'login_attempts',
        'last_attempt',
        'blocked_until',
        'failed_ip',
        'createdon',
        'createdby',
        'updatedon',
        'updatedby',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_lahir' => 'date',
        'jns_kelamin' => 'string',
        'pstatus' => 'string',
        'blokir' => 'string',
        'kodepos' => 'integer',
        'kodepos2' => 'integer',
        'tinggi_badan' => 'integer',
        'berat_badan' => 'integer',
        'login_attempts' => 'integer',
        'last_attempt' => 'timestamp',
        'blocked_until' => 'timestamp',
        'createdon' => 'timestamp',
        'updatedon' => 'timestamp',
    ];

    /**
     * Set the password attribute with hashing.
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Set the jns_kelamin attribute to ensure it's always stored as string.
     *
     * @param mixed $value
     * @return void
     */
    public function setJnsKelaminAttribute($value): void
    {
        $this->attributes['jns_kelamin'] = (string) $value;
    }

    /**
     * Set the pstatus attribute to ensure it's always stored as string.
     *
     * @param mixed $value
     * @return void
     */
    public function setPstatusAttribute($value): void
    {
        $this->attributes['pstatus'] = (string) $value;
    }

    /**
     * Set the blokir attribute to ensure it's always stored as string.
     *
     * @param mixed $value
     * @return void
     */
    public function setBlokirAttribute($value): void
    {
        $this->attributes['blokir'] = (string) $value;
    }

    /**
     * Relasi ke unit kerja
     *
     * @return BelongsTo
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'kode_unit_kerja', 'kode');
    }

    /**
     * Relasi ke agama
     *
     * @return BelongsTo
     */
    public function agama(): BelongsTo
    {
        return $this->belongsTo(Agama::class, 'kode_agama', 'kode');
    }

    /**
     * Relasi ke golongan darah
     *
     * @return BelongsTo
     */
    public function golonganDarah(): BelongsTo
    {
        return $this->belongsTo(TabelGolonganDarah::class, 'kode_golongan_darah', 'kode');
    }

    /**
     * Relasi ke status nikah
     *
     * @return BelongsTo
     */
    public function statusNikah(): BelongsTo
    {
        return $this->belongsTo(TabelStatusNikah::class, 'kode_status_nikah', 'kode');
    }

    /**
     * Relasi ke status kepegawaian
     *
     * @return BelongsTo
     */
    public function statusKepegawaian(): BelongsTo
    {
        return $this->belongsTo(TabelStatusKepegawaian::class, 'kode_status_kepegawaian', 'kode');
    }

    /**
     * Relasi ke propinsi (alamat utama)
     *
     * @return BelongsTo
     */
    public function propinsi(): BelongsTo
    {
        return $this->belongsTo(TabelPropinsi::class, 'kode_propinsi', 'kode');
    }

    /**
     * Relasi ke propinsi kedua (alamat kedua)
     *
     * @return BelongsTo
     */
    public function propinsi2(): BelongsTo
    {
        return $this->belongsTo(TabelPropinsi::class, 'kode_propinsi2', 'kode');
    }

    /**
     * Relasi ke jabatan utama
     *
     * @return BelongsTo
     */
    public function jabatanUtama(): BelongsTo
    {
        return $this->belongsTo(JabatanUtama::class, 'kode_jabatan_utama', 'kode');
    }

    /**
     * Relasi ke jenjang pendidikan
     *
     * @return BelongsTo
     */
    public function jenjangPendidikan(): BelongsTo
    {
        return $this->belongsTo(JenjangPendidikan::class, 'kode_jenjang_pendidikan', 'kode');
    }

    /**
     * Relasi ke riwayat pendidikan
     *
     * @return HasMany
     */
    public function riwayatPendidikan(): HasMany
    {
        return $this->hasMany(RiwayatPendidikan::class, 'nik_data_pegawai', 'nip');
    }

    /**
     * Scope untuk pegawai yang tidak diblokir
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotBlocked($query)
    {
        return $query->where('blokir', 'Tidak');
    }

    /**
     * Scope untuk pegawai aktif
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('pstatus', '1');
    }

    /**
     * Check if user is currently blocked
     *
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->blocked_until && $this->blocked_until->isFuture();
    }

    /**
     * Get full name with NIP
     *
     * @return string
     */
    public function getFullNameWithNipAttribute(): string
    {
        return $this->nama_lengkap . ' (' . $this->nip . ')';
    }

    /**
     * Get gender label
     *
     * @return string
     */
    public function getGenderLabelAttribute(): string
    {
        return $this->jns_kelamin === '1' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Get status label
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->pstatus === '1' ? 'Aktif' : 'Tidak Aktif';
    }

    /**
     * Get blocked status label
     *
     * @return string
     */
    public function getBlockedLabelAttribute(): string
    {
        return $this->blokir === 'Ya' ? 'Diblokir' : 'Normal';
    }
}