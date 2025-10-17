<?php

namespace App\Filament\Resources\DataRiwayatPasangans\Schemas;

use Filament\Schemas\Schema;

class DataRiwayatPasanganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Fieldset::make('Informasi Data Pasangan')
                    ->schema([
                        \Filament\Forms\Components\Select::make('nik_data_pegawai')
                            ->label('Pegawai')
                            ->required()
                            ->options(\App\Models\DataPegawai::query()
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

                        \Filament\Forms\Components\TextInput::make('nama_pasangan')
                            ->label('Nama Pasangan')
                            ->maxLength(255)
                            ->placeholder('Masukkan nama lengkap pasangan'),

                        \Filament\Forms\Components\Select::make('hubungan')
                            ->label('Hubungan')
                            ->required()
                            ->options([
                                'Suami' => 'Suami',
                                'Istri' => 'Istri',
                            ])
                            ->native(false)
                            ->placeholder('Pilih hubungan'),

                        \Filament\Forms\Components\TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->maxLength(100)
                            ->placeholder('Masukkan tempat lahir pasangan'),

                        \Filament\Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->placeholder('Pilih tanggal lahir'),

                        \Filament\Forms\Components\Select::make('kode_jenjang_pendidikan')
                            ->label('Jenjang Pendidikan')
                            ->options(\App\Models\JenjangPendidikan::query()
                                ->where('status', '1')
                                ->orderBy('urut')
                                ->pluck('nama_jenjang_pendidikan', 'kode')
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->placeholder('Pilih jenjang pendidikan'),

                        \Filament\Forms\Components\Select::make('kode_tabel_pekerjaan')
                            ->label('Pekerjaan')
                            ->options(\App\Models\TabelPekerjaan::query()
                                ->where('status', '1')
                                ->orderBy('urut')
                                ->pluck('nama_pekerjaan', 'kode')
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->placeholder('Pilih pekerjaan'),

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
