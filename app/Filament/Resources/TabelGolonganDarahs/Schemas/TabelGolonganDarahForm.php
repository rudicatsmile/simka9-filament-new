<?php

namespace App\Filament\Resources\TabelGolonganDarahs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class TabelGolonganDarahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode')
                    ->label('Kode')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan kode golongan darah')
                    ->helperText('Kode golongan darah maksimal 255 karakter'),

                TextInput::make('nama_golongan_darah')
                    ->label('Nama Golongan Darah')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama golongan darah')
                    ->helperText('Nama golongan darah maksimal 255 karakter'),

                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->default('1')
                    ->helperText('Pilih status golongan darah'),

                TextInput::make('urut')
                    ->label('Urutan')
                    ->required()
                    ->numeric()
                    ->placeholder('Masukkan urutan')
                    ->helperText('Urutan harus berupa angka'),
            ]);
    }
}
