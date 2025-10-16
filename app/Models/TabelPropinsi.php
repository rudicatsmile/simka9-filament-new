<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model TabelPropinsi
 * 
 * Model ini merepresentasikan data propinsi dalam sistem
 * 
 * @property int $id
 * @property string $kode
 * @property string $nama_propinsi
 * @property string $status
 * @property int $urut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @package App\Models
 * @author Laravel Filament
 * @version 1.0.0
 */
class TabelPropinsi extends Model
{
    /** @use HasFactory<\Database\Factories\TabelPropinsiFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tabel_propinsi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode',
        'nama_propinsi',
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
    ];

    /**
     * Set the status attribute.
     * Ensure status is always stored as string for ENUM compatibility
     *
     * @param mixed $value
     * @return void
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = (string) $value;
    }
}