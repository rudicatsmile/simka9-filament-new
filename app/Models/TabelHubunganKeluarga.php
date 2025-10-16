<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TabelHubunganKeluarga Model
 * 
 * Model untuk mengelola data hubungan keluarga.
 * Menyimpan informasi tentang jenis hubungan keluarga seperti Ayah, Ibu, Anak, dll.
 * 
 * @package App\Models
 * @author Laravel Filament
 * @version 1.0.0
 */
class TabelHubunganKeluarga extends Model
{
    /** @use HasFactory<\Database\Factories\TabelHubunganKeluargaFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tabel_hubungan_keluarga';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode',
        'nama_hubungan_keluarga',
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