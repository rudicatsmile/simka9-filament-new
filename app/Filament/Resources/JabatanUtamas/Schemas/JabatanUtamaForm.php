<?php

namespace App\Filament\Resources\JabatanUtamas\Schemas;

use App\Models\UnitKerja;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

/**
 * Form schema untuk JabatanUtama
 * 
 * Class ini mengkonfigurasi form input untuk model JabatanUtama
 * termasuk validasi dan komponen form yang diperlukan
 */
class JabatanUtamaForm
{
    /**
     * Konfigurasi schema form untuk JabatanUtama
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
                    ->placeholder('Masukkan kode jabatan utama')
                    ->columnSpan(1),
                Select::make('kode_unit_kerja')
                    ->label('Unit Kerja')
                    ->options(UnitKerja::where('status', '1')->pluck('nama_unit_kerja', 'kode'))
                    ->required()
                    ->searchable()
                    ->placeholder('Pilih unit kerja')
                    ->columnSpan(1),
                TextInput::make('nama_jabatan_utama')
                    ->label('Nama Jabatan Utama')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama jabatan utama')
                    ->columnSpan(2),
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
