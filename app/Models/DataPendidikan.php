<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk data pendidikan pegawai
 * 
 * @property int $id
 * @property string $nip_pegawai
 * @property string $jenjang_pendidikan
 * @property string $nama_sekolah
 * @property string|null $jurusan
 * @property int $tahun_lulus
 * @property float|null $nilai_ijazah
 * @property string|null $nomor_ijazah
 * @property string $status
 * @property int $urut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class DataPendidikan extends Model
{
    /** @use HasFactory<\Database\Factories\DataPendidikanFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_pendidikans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nip_pegawai',
        'jenjang_pendidikan',
        'nama_sekolah',
        'jurusan',
        'tahun_lulus',
        'nilai_ijazah',
        'nomor_ijazah',
        'status',
        'urut',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tahun_lulus' => 'integer',
        'nilai_ijazah' => 'decimal:2',
        'status' => 'string',
        'urut' => 'integer',
    ];

    /**
     * Set the status attribute.
     * Ensures the status is always stored as a string to prevent SQL truncation error.
     *
     * @param mixed $value
     * @return void
     */
    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = (string) $value;
    }

    /**
     * Relasi ke data pegawai
     */
    public function pegawai()
    {
        return $this->belongsTo(DataPegawai::class, 'nip_pegawai', 'nip');
    }
}
