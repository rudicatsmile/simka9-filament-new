<?php

namespace App\Filament\Resources\TabelHubunganKeluargas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class TabelHubunganKeluargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode')
                    ->label('Kode')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan kode hubungan keluarga')
                    ->helperText('Kode hubungan keluarga maksimal 255 karakter'),

                TextInput::make('nama_hubungan_keluarga')
                    ->label('Nama Hubungan Keluarga')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama hubungan keluarga')
                    ->helperText('Nama hubungan keluarga maksimal 255 karakter'),

                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->default('1')
                    ->helperText('Pilih status hubungan keluarga'),

                TextInput::make('urut')
                    ->label('Urutan')
                    ->required()
                    ->numeric()
                    ->placeholder('Masukkan urutan')
                    ->helperText('Urutan harus berupa angka'),
            ]);
    }
}
