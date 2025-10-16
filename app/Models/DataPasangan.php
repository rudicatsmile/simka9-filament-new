<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk data pasangan pegawai
 * 
 * @property int $id
 * @property string $nip_pegawai
 * @property string $nama_pasangan
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string|null $pekerjaan
 * @property string|null $alamat
 * @property string|null $telepon
 * @property string $status
 * @property int $urut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class DataPasangan extends Model
{
    /** @use HasFactory<\Database\Factories\DataPasanganFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_pasangans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nip_pegawai',
        'nama_pasangan',
        'tempat_lahir',
        'tanggal_lahir',
        'pekerjaan',
        'alamat',
        'telepon',
        'status',
        'urut',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
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
