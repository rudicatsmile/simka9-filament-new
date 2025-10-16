<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel status nikah
 * 
 * @property int $id
 * @property string $kode
 * @property string $nama_status_nikah
 * @property string $status
 * @property int $urut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class TabelStatusNikah extends Model
{
    /** @use HasFactory<\Database\Factories\TabelStatusNikahFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tabel_status_nikahs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode',
        'nama_status_nikah',
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

    /**
     * Set the status attribute to ensure it's always stored as string.
     *
     * @param mixed $value
     * @return void
     */
    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = (string) $value;
    }
}
