<?php

namespace App\Filament\Resources\TabelPekerjaans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

/**
 * TabelPekerjaanForm
 * 
 * Konfigurasi form untuk TabelPekerjaan Resource.
 * Menyediakan komponen form untuk input data pekerjaan.
 * 
 * @package App\Filament\Resources\TabelPekerjaans\Schemas
 * @author Laravel Filament
 * @version 1.0.0
 */
class TabelPekerjaanForm
{
    /**
     * Konfigurasi schema form untuk TabelPekerjaan
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
                    ->placeholder('Masukkan kode pekerjaan')
                    ->helperText('Kode pekerjaan maksimal 255 karakter'),

                TextInput::make('nama_pekerjaan')
                    ->label('Nama Pekerjaan')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama pekerjaan')
                    ->helperText('Nama pekerjaan maksimal 255 karakter'),

                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->default('1')
                    ->helperText('Pilih status pekerjaan'),

                TextInput::make('urut')
                    ->label('Urutan')
                    ->required()
                    ->numeric()
                    ->placeholder('Masukkan urutan')
                    ->helperText('Urutan harus berupa angka'),
            ]);
    }
}