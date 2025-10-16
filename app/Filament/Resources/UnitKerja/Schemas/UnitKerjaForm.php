<?php

namespace App\Filament\Resources\UnitKerja\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class UnitKerjaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode')
                    ->label('Kode')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan kode unit kerja')
                    ->columnSpan(1),
                TextInput::make('nama_unit_kerja')
                    ->label('Nama Unit Kerja')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama unit kerja')
                    ->columnSpan(1),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->default('1')
                    ->required()
                    ->columnSpan(1),
                TextInput::make('urut')
                    ->label('Urut')
                    ->numeric()
                    ->required()
                    ->placeholder('Masukkan urutan')
                    ->columnSpan(1),
            ])
            ->columns(2);
    }
}