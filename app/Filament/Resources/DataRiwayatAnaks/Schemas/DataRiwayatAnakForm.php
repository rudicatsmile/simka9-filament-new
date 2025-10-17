<?php

namespace App\Filament\Resources\DataRiwayatAnaks\Schemas;

use App\Models\DataPegawai;
use App\Models\TabelHubunganKeluarga;
use App\Models\JenjangPendidikan;
use App\Models\TabelPekerjaan;
use Filament\Schemas\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\Textarea;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Schema;

/**
 * DataRiwayatAnakForm
 * 
 * Form schema untuk DataRiwayatAnak Resource dengan real-time filtering
 */
class DataRiwayatAnakForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Pegawai')
                    ->schema([
                        Select::make('nik_data_pegawai')
                            ->label('Pegawai')
                            ->options(function () {
                                return DataPegawai::with('unitKerja')
                                    ->get()
                                    ->mapWithKeys(function ($pegawai) {
                                        $unitKerja = $pegawai->unitKerja?->nama ?? 'Tidak Ada Unit';
                                        return [$pegawai->nik => "{$pegawai->nama} - {$unitKerja}"];
                                    });
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Auto-set urut when employee changes
                                if ($state) {
                                    $maxUrut = \App\Models\DataRiwayatAnak::where('nik_data_pegawai', $state)->max('urut');
                                    $set('urut', ($maxUrut ?? 0) + 1);
                                }
                            })
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Section::make('Data Anak')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama lengkap anak'),

                        Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Jakarta'),

                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->format('Y-m-d')
                            ->maxDate(now()),

                        Select::make('id_tabel_hubungan_keluarga')
                            ->label('Hubungan Keluarga')
                            ->options(TabelHubunganKeluarga::all()->pluck('nama', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),

                        TextInput::make('urut')
                            ->label('Urutan Anak')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1)
                            ->helperText('Urutan anak dalam keluarga'),
                    ])
                    ->columns(2),

                Section::make('Pendidikan & Pekerjaan')
                    ->schema([
                        Select::make('id_jenjang_pendidikan')
                            ->label('Jenjang Pendidikan')
                            ->options(JenjangPendidikan::all()->pluck('nama', 'id'))
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih jenjang pendidikan (opsional)'),

                        Select::make('id_tabel_pekerjaan')
                            ->label('Pekerjaan')
                            ->options(TabelPekerjaan::all()->pluck('nama', 'id'))
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih pekerjaan (opsional)'),

                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Keterangan tambahan (opsional)'),
                    ])
                    ->columns(2),
            ]);
    }
}
