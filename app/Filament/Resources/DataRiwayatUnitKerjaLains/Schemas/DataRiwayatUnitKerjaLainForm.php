<?php

namespace App\Filament\Resources\DataRiwayatUnitKerjaLains\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;

class DataRiwayatUnitKerjaLainForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Fieldset::make('Informasi Unit Kerja Lain')
                    ->schema([
                        \Filament\Forms\Components\Select::make('nik_data_pegawai')
                            ->label('Pegawai')
                            ->required()
                            ->options(
                                \App\Models\DataPegawai::query()
                                    ->orderBy('nama_lengkap')
                                    ->get()
                                    ->mapWithKeys(fn($pegawai) => [
                                        $pegawai->nik => "{$pegawai->nama_lengkap} ({$pegawai->nip})"
                                    ])
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->placeholder('Pilih pegawai'),

                        \Filament\Forms\Components\Toggle::make('is_bekerja_di_tempat_lain')
                            ->label('Bekerja di Tempat Lain')
                            ->default(false)
                            ->live()
                            ->dehydrateStateUsing(function ($state) {
                                $truthy = [true, 1, '1', 'true', 'on', 'ya', 'yes'];
                                return in_array($state, $truthy, true) ? '1' : '0';
                            })
                            ->dehydrated(true)
                            ->helperText('Aktifkan jika pegawai bekerja di tempat lain'),

                        // Status hanya tampil & wajib diisi jika toggle aktif
                        \Filament\Forms\Components\Select::make('status')
                            ->label('Status')
                            ->required(fn(Get $get) => (int) $get('is_bekerja_di_tempat_lain') === 1)
                            ->options([
                                'aktif' => 'Aktif',
                                'tidak_aktif' => 'Tidak Aktif',
                                'selesai' => 'Selesai',
                            ])
                            ->default('aktif')
                            ->native(false)
                            ->live()
                            ->visible(fn(Get $get) => (int) $get('is_bekerja_di_tempat_lain') === 1)
                            ->placeholder('Pilih status'),

                        // Nama hanya tampil jika toggle aktif
                        \Filament\Forms\Components\TextInput::make('nama')
                            ->label('Nama Instansi/Perusahaan')
                            ->maxLength(255)
                            ->visible(fn(Get $get) => (int) $get('is_bekerja_di_tempat_lain') === 1)
                            ->required(fn(Get $get) => (int) $get('is_bekerja_di_tempat_lain') === 1)
                            ->dehydrated(fn(Get $get) => (int) $get('is_bekerja_di_tempat_lain') === 1)
                            ->placeholder('Masukkan nama instansi atau perusahaan'),

                        // Jabatan hanya tampil jika toggle aktif
                        \Filament\Forms\Components\TextInput::make('jabatan')
                            ->label('Jabatan')
                            ->maxLength(255)
                            ->visible(fn(Get $get) => (int) $get('is_bekerja_di_tempat_lain') === 1)
                            ->required(fn(Get $get) => (int) $get('is_bekerja_di_tempat_lain') === 1)
                            ->dehydrated(fn(Get $get) => (int) $get('is_bekerja_di_tempat_lain') === 1)
                            ->placeholder('Masukkan jabatan di instansi tersebut'),

                        // Fungsi hanya tampil jika toggle aktif
                        \Filament\Forms\Components\TextInput::make('fungsi')
                            ->label('Fungsi/Bidang Kerja')
                            ->maxLength(255)
                            ->visible(fn(Get $get) => (int) $get('is_bekerja_di_tempat_lain') === 1)
                            ->required(fn(Get $get) => (int) $get('is_bekerja_di_tempat_lain') === 1)
                            ->dehydrated(fn(Get $get) => (int) $get('is_bekerja_di_tempat_lain') === 1)
                            ->placeholder('Masukkan fungsi atau bidang kerja'),

                        \Filament\Forms\Components\TextInput::make('urut')
                            ->label('Urutan')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->default(1)
                            ->placeholder('Masukkan urutan prioritas'),
                    ])
                    ->columns(2),
            ]);
    }
}
