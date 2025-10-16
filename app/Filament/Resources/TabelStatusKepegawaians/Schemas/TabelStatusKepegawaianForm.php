<?php

namespace App\Filament\Resources\TabelStatusKepegawaians\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TabelStatusKepegawaianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode')
                    ->label('Kode')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->placeholder('Masukkan kode status kepegawaian'),

                TextInput::make('nama_status_kepegawaian')
                    ->label('Nama Status Kepegawaian')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama status kepegawaian'),

                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ])
                    ->default('1')
                    ->native(false),

                TextInput::make('urut')
                    ->label('Urutan')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->placeholder('Masukkan urutan'),
            ]);
    }
}
