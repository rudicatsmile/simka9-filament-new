<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model TabelJenisPelatihan
 * 
 * Model ini merepresentasikan data jenis pelatihan dalam sistem
 * 
 * @property int $id
 * @property string $kode
 * @property string $nama_jenis_pelatihan
 * @property string $status
 * @property int $urut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class TabelJenisPelatihan extends Model
{
    /** @use HasFactory<\Database\Factories\TabelJenisPelatihanFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tabel_jenis_pelatihan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode',
        'nama_jenis_pelatihan',
        'status',
        'urut',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
        'urut' => 'integer',
    ];
}