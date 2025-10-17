<?php

namespace App\Filament\Resources\RiwayatPendidikans\Schemas;

use Filament\Schemas\Schema;

class RiwayatPendidikanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Fieldset::make('Informasi Riwayat Pendidikan')
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

                        \Filament\Forms\Components\Select::make('kode_jenjang_pendidikan')
                            ->label('Jenjang Pendidikan')
                            ->required()
                            ->options(\App\Models\JenjangPendidikan::query()
                                ->where('status', '1')
                                ->orderBy('urut')
                                ->pluck('nama_jenjang_pendidikan', 'kode')
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->placeholder('Pilih jenjang pendidikan'),

                        \Filament\Forms\Components\TextInput::make('nama_sekolah')
                            ->label('Nama Sekolah/Institusi')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama sekolah atau institusi pendidikan'),

                        \Filament\Forms\Components\TextInput::make('tahun_ijazah')
                            ->label('Tahun Ijazah')
                            ->required()
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y') + 10)
                            ->placeholder('Masukkan tahun ijazah'),

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
