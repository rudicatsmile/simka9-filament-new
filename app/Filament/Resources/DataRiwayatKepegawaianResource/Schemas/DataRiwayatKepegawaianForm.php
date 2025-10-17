<?php

namespace App\Filament\Resources\DataRiwayatKepegawaianResource\Schemas;

use App\Models\DataPegawai;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;

/**
 * DataRiwayatKepegawaianForm
 *
 * Form schema untuk DataRiwayatKepegawaian Resource
 */
class DataRiwayatKepegawaianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Pegawai')
                    ->schema([
                        Select::make('nik_data_pegawai')
                            ->label('Pegawai')
                            ->options(
                                DataPegawai::query()
                                    ->orderBy('nama_lengkap')
                                    ->get()
                                    ->mapWithKeys(fn($pegawai) => [
                                        $pegawai->nik => "{$pegawai->nama_lengkap} ({$pegawai->nip})"
                                    ])
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Section::make('Detail Riwayat Kepegawaian')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Riwayat')
                            ->maxLength(255)
                            ->placeholder('Contoh: Pengangkatan CPNS, Kenaikan Pangkat, dll'),

                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->displayFormat('d/m/Y')
                            ->format('Y-m-d'),

                        TextInput::make('nomor')
                            ->label('Nomor SK/Dokumen')
                            ->maxLength(255)
                            ->placeholder('Contoh: KEP-001/2024'),

                        TextInput::make('urut')
                            ->label('Urutan')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1),

                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(3)
                            ->columnSpanFull(),

                        FileUpload::make('berkas')
                            ->label('Berkas Dokumen')
                            ->directory('kepegawaian')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(5120) // 5MB
                            ->downloadable()
                            ->openable()
                            ->previewable(false)
                            ->columnSpanFull()
                            ->helperText('Format yang diizinkan: PDF, DOC, DOCX. Maksimal 5MB.'),
                    ])
                    ->columns(2),
            ]);
    }
}
