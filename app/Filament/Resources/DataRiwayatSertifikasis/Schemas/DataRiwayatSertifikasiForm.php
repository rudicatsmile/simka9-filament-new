<?php

namespace App\Filament\Resources\DataRiwayatSertifikasis\Schemas;

use App\Models\DataPegawai;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

/**
 * DataRiwayatSertifikasiForm
 *
 * Form schema untuk DataRiwayatSertifikasi Resource
 */
class DataRiwayatSertifikasiForm
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

                Section::make('Detail Sertifikasi')
                    ->schema([
                        Toggle::make('is_sertifikasi')
                            ->label('Status Sertifikasi')
                            ->helperText('Aktifkan jika ini adalah sertifikasi resmi')
                            ->default(true)
                            ->columnSpanFull(),

                        TextInput::make('nama')
                            ->label('Nama Sertifikasi')
                            ->maxLength(255)
                            ->required()
                            ->placeholder('Contoh: Sertifikat Kompetensi Guru, Sertifikat Profesi, dll'),

                        TextInput::make('nomor')
                            ->label('Nomor Sertifikat')
                            ->maxLength(255)
                            ->placeholder('Contoh: CERT-001/2024'),

                        TextInput::make('tahun')
                            ->label('Tahun Sertifikasi')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y') + 5)
                            ->default(date('Y'))
                            ->required(),

                        TextInput::make('urut')
                            ->label('Urutan')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1),
                    ])
                    ->columns(2),

                Section::make('Informasi Inpasing')
                    ->schema([
                        TextInput::make('induk_inpasing')
                            ->label('Induk Inpasing')
                            ->maxLength(255)
                            ->placeholder('Nomor induk inpasing jika ada'),

                        TextInput::make('sk_inpasing')
                            ->label('SK Inpasing')
                            ->maxLength(255)
                            ->placeholder('Nomor SK inpasing jika ada'),

                        TextInput::make('tahun_inpasing')
                            ->label('Tahun Inpasing')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y') + 5)
                            ->placeholder('Tahun inpasing jika ada'),
                    ])
                    ->columns(3)
                    ->collapsible()
                    ->collapsed(),

                Section::make('Berkas Dokumen')
                    ->schema([
                        FileUpload::make('berkas')
                            ->label('Berkas Sertifikat')
                            ->directory('sertifikasi')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->maxSize(5120) // 5MB
                            ->downloadable()
                            ->openable()
                            ->previewable()
                            ->columnSpanFull()
                            ->helperText('Format yang diizinkan: PDF, JPG, PNG. Maksimal 5MB.'),
                    ])
                    ->columns(1),
            ]);
    }
}
