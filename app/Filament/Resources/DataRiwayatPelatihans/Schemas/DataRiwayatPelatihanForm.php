<?php

namespace App\Filament\Resources\DataRiwayatPelatihans\Schemas;

use App\Models\DataPegawai;
use Filament\Schemas\Schema;
use App\Models\TabelJenisPelatihan;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
// use Filament\Tables\Columns\Layout\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;

class DataRiwayatPelatihanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(2)
                    ->schema([
                        Select::make('nik_data_pegawai')
                            ->label('Pegawai')
                            ->options(function () {
                                return DataPegawai::query()
                                    ->select(['nik', 'nama_lengkap', 'nip'])
                                    ->get()
                                    ->mapWithKeys(function ($pegawai) {
                                        return [
                                            $pegawai->nik => $pegawai->nama_lengkap . ' (' . $pegawai->nip . ')',
                                        ];
                                    });
                            })
                            ->searchable()
                            ->required()
                            ->placeholder('Pilih Pegawai'),

                        Select::make('kode_tabel_jenis_pelatihan')
                            ->label('Jenis Pelatihan')
                            ->options(function () {
                                return TabelJenisPelatihan::query()
                                    ->select(['kode', 'nama_jenis_pelatihan'])
                                    ->get()
                                    ->mapWithKeys(function ($jenis) {
                                        return [
                                            $jenis->kode => $jenis->kode . ' - ' . $jenis->nama_jenis_pelatihan,
                                        ];
                                    });
                            })
                            ->searchable()
                            ->required()
                            ->placeholder('Pilih Jenis Pelatihan'),

                        TextInput::make('nama')
                            ->label('Nama Pelatihan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama pelatihan'),

                        TextInput::make('penyelenggara')
                            ->label('Penyelenggara')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama penyelenggara'),

                        TextInput::make('angkatan')
                            ->label('Angkatan')
                            ->maxLength(50)
                            ->placeholder('Masukkan angkatan (opsional)'),

                        TextInput::make('nomor')
                            ->label('Nomor Sertifikat')
                            ->maxLength(100)
                            ->placeholder('Masukkan nomor sertifikat (opsional)'),

                        DatePicker::make('tanggal')
                            ->label('Tanggal Pelatihan')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->format('Y-m-d'),

                        DatePicker::make('tanggal_sertifikat')
                            ->label('Tanggal Sertifikat')
                            ->displayFormat('d/m/Y')
                            ->format('Y-m-d')
                            ->placeholder('Pilih tanggal sertifikat (opsional)'),

                        TextInput::make('urut')
                            ->label('Urutan')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(999)
                            ->default(1)
                            ->required()
                            ->placeholder('Masukkan urutan'),
                    ]),

                FileUpload::make('berkas')
                    ->label('Berkas Sertifikat')
                    ->directory('pelatihan-certificates')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'])
                    ->maxSize(5120) // 5MB
                    ->downloadable()
                    ->previewable()
                    ->columnSpanFull()
                    ->helperText('Upload berkas sertifikat (PDF, JPG, PNG). Maksimal 5MB.'),
            ]);
    }
}
