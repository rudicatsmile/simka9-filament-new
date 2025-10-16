<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TabelGolonganDarah Model
 * 
 * Represents blood type data in the system
 * 
 * @property int $id
 * @property string $kode
 * @property string $nama_golongan_darah
 * @property string $status
 * @property int $urut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class TabelGolonganDarah extends Model
{
    /** @use HasFactory<\Database\Factories\TabelGolonganDarahFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tabel_golongan_darah';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode',
        'nama_golongan_darah',
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
     * Set the status attribute.
     * Ensure status is always stored as string for ENUM compatibility
     *
     * @param mixed $value
     * @return void
     */
    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = (string) $value;
    }
}
