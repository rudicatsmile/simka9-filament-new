<?php

namespace App\Filament\Resources\TabelJenisPelatihans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

/**
 * TabelJenisPelatihanForm
 * 
 * Konfigurasi form untuk TabelJenisPelatihan Resource.
 * Menyediakan komponen form untuk input data jenis pelatihan.
 * 
 * @package App\Filament\Resources\TabelJenisPelatihans\Schemas
 * @author Laravel Filament
 * @version 1.0.0
 */
class TabelJenisPelatihanForm
{
    /**
     * Konfigurasi schema form untuk TabelJenisPelatihan
     * 
     * @param Schema $schema
     * @return Schema
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode')
                    ->label('Kode')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan kode jenis pelatihan')
                    ->helperText('Kode jenis pelatihan maksimal 255 karakter'),

                TextInput::make('nama_jenis_pelatihan')
                    ->label('Nama Jenis Pelatihan')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama jenis pelatihan')
                    ->helperText('Nama jenis pelatihan maksimal 255 karakter'),

                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->default('1')
                    ->helperText('Pilih status jenis pelatihan'),

                TextInput::make('urut')
                    ->label('Urutan')
                    ->required()
                    ->numeric()
                    ->placeholder('Masukkan urutan')
                    ->helperText('Urutan harus berupa angka'),
            ]);
    }
}