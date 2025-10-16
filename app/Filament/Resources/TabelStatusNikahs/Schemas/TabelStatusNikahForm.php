<?php

namespace App\Filament\Resources\TabelStatusNikahs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

/**
 * TabelStatusNikahForm
 * 
 * Konfigurasi form untuk TabelStatusNikah Resource.
 * Menyediakan field input untuk data status nikah.
 * 
 * @package App\Filament\Resources\TabelStatusNikahs\Schemas
 * @author Laravel Filament
 * @version 1.0.0
 */
class TabelStatusNikahForm
{
    /**
     * Konfigurasi form schema untuk TabelStatusNikah
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
                    ->placeholder('Masukkan kode status nikah')
                    ->helperText('Kode status nikah maksimal 255 karakter'),

                TextInput::make('nama_status_nikah')
                    ->label('Nama Status Nikah')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama status nikah')
                    ->helperText('Nama status nikah maksimal 255 karakter'),

                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->default('1')
                    ->helperText('Pilih status aktif/tidak aktif'),

                TextInput::make('urut')
                    ->label('Urutan')
                    ->required()
                    ->numeric()
                    ->placeholder('Masukkan urutan')
                    ->helperText('Urutan harus berupa angka'),
            ]);
    }
}
