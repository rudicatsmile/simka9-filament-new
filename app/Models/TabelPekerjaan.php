<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model TabelPekerjaan
 * 
 * Model ini merepresentasikan data pekerjaan dalam sistem
 * 
 * @property int $id
 * @property string $kode
 * @property string $nama_pekerjaan
 * @property string $status
 * @property int $urut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class TabelPekerjaan extends Model
{
    /** @use HasFactory<\Database\Factories\TabelPekerjaanFactory> */
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model
     *
     * @var string
     */
    protected $table = 'tabel_pekerjaan';

    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode',
        'nama_pekerjaan',
        'status',
        'urut',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
        'urut' => 'integer',
    ];
}