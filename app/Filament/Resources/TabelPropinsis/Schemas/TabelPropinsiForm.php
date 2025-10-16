<?php

namespace App\Filament\Resources\TabelPropinsis\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class TabelPropinsiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode')
                    ->label('Kode')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan kode propinsi')
                    ->helperText('Kode propinsi maksimal 255 karakter'),

                TextInput::make('nama_propinsi')
                    ->label('Nama Propinsi')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama propinsi')
                    ->helperText('Nama propinsi maksimal 255 karakter'),

                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->default('1')
                    ->helperText('Pilih status propinsi'),

                TextInput::make('urut')
                    ->label('Urutan')
                    ->required()
                    ->numeric()
                    ->placeholder('Masukkan urutan')
                    ->helperText('Urutan harus berupa angka'),
            ]);
    }
}
