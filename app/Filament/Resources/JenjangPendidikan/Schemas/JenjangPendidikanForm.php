<?php

namespace App\Filament\Resources\JenjangPendidikan\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class JenjangPendidikanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode')
                    ->label('Kode')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan kode jenjang pendidikan')
                    ->helperText('Kode jenjang pendidikan maksimal 255 karakter'),

                TextInput::make('nama_jenjang_pendidikan')
                    ->label('Nama Jenjang Pendidikan')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama jenjang pendidikan')
                    ->helperText('Nama jenjang pendidikan maksimal 255 karakter'),

                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->default('1')
                    ->helperText('Pilih status jenjang pendidikan'),

                TextInput::make('urut')
                    ->label('Urutan')
                    ->required()
                    ->numeric()
                    ->placeholder('Masukkan urutan')
                    ->helperText('Urutan harus berupa angka'),
            ]);
    }
}