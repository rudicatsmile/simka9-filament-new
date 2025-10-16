<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk data anak pegawai
 * 
 * @property int $id
 * @property string $nip_pegawai
 * @property string $nama_anak
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $jenis_kelamin
 * @property string $status_anak
 * @property string|null $pendidikan_terakhir
 * @property string $status
 * @property int $urut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class DataAnak extends Model
{
    /** @use HasFactory<\Database\Factories\DataAnakFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_anaks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nip_pegawai',
        'nama_anak',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_anak',
        'pendidikan_terakhir',
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
