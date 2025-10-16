<?php

namespace App\Filament\Resources\DataPegawais\Schemas;

use App\Models\Agama;
use App\Models\UnitKerja;
use App\Models\JabatanUtama;
use Filament\Schemas\Schema;
use App\Models\TabelPropinsi;
use App\Models\TabelStatusNikah;
use App\Models\JenjangPendidikan;
use App\Models\TabelGolonganDarah;
use Filament\Forms\Components\Select;
use App\Models\TabelStatusKepegawaian;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;

class DataPegawaiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Informasi Dasar')
                    ->schema([
                        TextInput::make('nip')
                            ->label('NIP')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Masukkan NIP pegawai'),

                        TextInput::make('nik')
                            ->label('NIK')
                            ->maxLength(255)
                            ->placeholder('Masukkan NIK pegawai'),

                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Masukkan nama lengkap pegawai'),

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required()
                            ->minLength(6)
                            ->maxLength(100)
                            ->placeholder('Masukkan password'),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Masukkan email pegawai'),

                        TextInput::make('no_tlp')
                            ->label('No. Telepon')
                            ->maxLength(15)
                            ->placeholder('Masukkan nomor telepon'),

                        // Foto dihapus dari sini
                    ])
                    ->columns(2),

                Fieldset::make('Data Pribadi')
                    ->schema([
                        TextInput::make('tmp_lahir')
                            ->label('Tempat Lahir')
                            ->maxLength(50)
                            ->placeholder('Masukkan tempat lahir'),

                        DatePicker::make('tgl_lahir')
                            ->label('Tanggal Lahir')
                            ->placeholder('Pilih tanggal lahir'),

                        Select::make('jns_kelamin')
                            ->label('Jenis Kelamin')
                            ->required()
                            ->options([
                                '1' => 'Laki-laki',
                                '0' => 'Perempuan',
                            ])
                            ->native(false),

                        Select::make('kode_agama')
                            ->label('Agama')
                            ->required()
                            ->options(Agama::pluck('nama_agama', 'kode'))
                            ->searchable()
                            ->native(false),

                        Select::make('kode_golongan_darah')
                            ->label('Golongan Darah')
                            ->options(TabelGolonganDarah::pluck('nama_golongan_darah', 'kode'))
                            ->searchable()
                            ->native(false),

                        Select::make('kode_status_nikah')
                            ->label('Status Nikah')
                            ->options(TabelStatusNikah::pluck('nama_status_nikah', 'kode'))
                            ->searchable()
                            ->native(false),
                    ])
                    ->columns(2),

                Fieldset::make('Data Kepegawaian')
                    ->schema([
                        Select::make('kode_unit_kerja')
                            ->label('Unit Kerja')
                            ->options(UnitKerja::pluck('nama_unit_kerja', 'kode'))
                            ->searchable()
                            ->native(false)
                            ->reactive(),

                        Select::make('kode_status_kepegawaian')
                            ->label('Status Kepegawaian')
                            ->options(TabelStatusKepegawaian::pluck('nama_status_kepegawaian', 'kode'))
                            ->default('PTY')
                            ->searchable()
                            ->native(false),

                        Select::make('pstatus')
                            ->label('Status Aktif')
                            ->required()
                            ->options([
                                '1' => 'Aktif',
                                '0' => 'Tidak Aktif',
                            ])
                            ->default('1')
                            ->native(false),

                        Select::make('blokir')
                            ->label('Status Blokir')
                            ->required()
                            ->options([
                                'Tidak' => 'Tidak',
                                'Ya' => 'Ya',
                            ])
                            ->default('Tidak')
                            ->native(false),


                    ])
                    ->columns(2),



                Fieldset::make('Jabatan & Tugas')
                    ->schema([
                        Select::make('kode_jabatan_utama')
                            ->label('Jabatan Utama')
                            ->options(JabatanUtama::pluck('nama_jabatan_utama', 'kode'))
                            ->searchable()
                            ->native(false)
                            ->hidden(fn(Get $get) => in_array($get('kode_unit_kerja'), ['008', '009', '010', '011', '012'])),

                        TextInput::make('unit_fungsi')
                            ->label('Fungsi Jabatan Utama')
                            ->maxLength(255)
                            ->placeholder('Masukkan fungsi jabatan utama')
                            ->hidden(fn(Get $get) => in_array($get('kode_unit_kerja'), ['008', '009', '010', '011', '012'])),

                        TextInput::make('unit_tugas')
                            ->label('Tugas Tambahan')
                            ->maxLength(255)
                            ->placeholder('Masukkan tugas tambahan')
                            ->hidden(fn(Get $get) => in_array($get('kode_unit_kerja'), [
                                '001',
                                'Yayasan', // sembunyikan untuk '001' atau label 'Yayasan'
                                '008',
                                '009',
                                '010',
                                '011',
                                '012', // sembunyikan seluruh grup
                            ])),

                        TextInput::make('unit_pelajaran')
                            ->label('Mata Pelajaran')
                            ->maxLength(100)
                            ->placeholder('Masukkan mata pelajaran yang di ampu')
                            ->hidden(fn(Get $get) => in_array($get('kode_unit_kerja'), [
                                '001',
                                'Yayasan', // sembunyikan untuk '001' atau label 'Yayasan'
                                '007',
                                'Tenaga Kependidikan Yayasan', // sembunyikan untuk '007' atau label terkait
                                '008',
                                '009',
                                '010',
                                '011',
                                '012', // sembunyikan seluruh grup
                            ])),
                    ])
                    ->columns(2),

                Fieldset::make('Data Pendidikan')
                    ->schema([
                        Select::make('kode_jenjang_pendidikan')
                            ->label('Jenjang Pendidikan')
                            ->options(JenjangPendidikan::pluck('nama_jenjang_pendidikan', 'kode'))
                            ->searchable()
                            ->native(false),

                        TextInput::make('program_studi')
                            ->label('Program Studi')
                            ->maxLength(50)
                            ->placeholder('Masukkan program studi'),

                        TextInput::make('nama_kampus')
                            ->label('Nama Kampus')
                            ->maxLength(50)
                            ->placeholder('Masukkan nama kampus'),

                        TextInput::make('tahun_lulus')
                            ->label('Tahun Lulus')
                            ->maxLength(15)
                            ->placeholder('Masukkan tahun lulus'),
                    ])
                    ->columns(2),

                Fieldset::make('Data Lainnya')
                    ->schema([
                        TextInput::make('bpjs')
                            ->label('BPJS')
                            ->maxLength(255)
                            ->placeholder('Masukkan nomor BPJS'),

                        TextInput::make('npwp')
                            ->label('NPWP')
                            ->maxLength(255)
                            ->placeholder('Masukkan nomor NPWP'),

                        TextInput::make('nuptk')
                            ->label('NUPTK')
                            ->maxLength(255)
                            ->placeholder('Masukkan nomor NUPTK'),

                        // TextInput::make('mulai_bekerja')
                        //     ->label('Mulai Bekerja')
                        //     ->maxLength(50)
                        //     ->placeholder('Masukkan tanggal mulai bekerja'),

                        DatePicker::make('mulai_bekerja')
                            ->label('Mulai Bekerja')
                            ->placeholder('Pilih tanggal mulai bekerja'),
                    ])
                    ->columns(2),


                Fieldset::make('Alamat KTP')
                    ->schema([
                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->maxLength(100)
                            ->rows(3)
                            ->placeholder('Masukkan alamat'),

                        Select::make('kode_propinsi')
                            ->label('Provinsi')
                            ->options(TabelPropinsi::pluck('nama_propinsi', 'kode'))
                            ->searchable()
                            ->native(false),

                        TextInput::make('kodepos')
                            ->label('Kode Pos')
                            ->numeric()
                            ->placeholder('Masukkan kode pos'),


                    ])
                    ->columns(2),

                Fieldset::make('Alamat Domisili')
                    ->schema([

                        Textarea::make('alamat2')
                            ->label('Alamat')
                            ->maxLength(100)
                            ->rows(3)
                            ->placeholder('Masukkan alamat  (opsional)'),

                        Select::make('kode_propinsi2')
                            ->label('Provinsi ')
                            ->options(TabelPropinsi::pluck('nama_propinsi', 'kode'))
                            ->searchable()
                            ->native(false),

                        TextInput::make('kodepos2')
                            ->label('Kode Pos ')
                            ->numeric()
                            ->placeholder('Masukkan kode pos '),
                    ])
                    ->columns(2),

                Fieldset::make('Informasi Tambahan')
                    ->schema([


                        Textarea::make('hobi')
                            ->label('Hobi')
                            ->maxLength(255)
                            ->rows(2)
                            ->placeholder('Masukkan hobi pegawai'),

                        TextInput::make('tinggi_badan')
                            ->label('Tinggi Badan (cm)')
                            ->numeric()
                            ->minValue(100)
                            ->maxValue(250)
                            ->placeholder('Masukkan tinggi badan'),

                        TextInput::make('berat_badan')
                            ->label('Berat Badan (kg)')
                            ->numeric()
                            ->minValue(30)
                            ->maxValue(200)
                            ->placeholder('Masukkan berat badan'),
                    ])
                    ->columns(2),


                Fieldset::make('Foto')
                    ->schema([
                        FileUpload::make('foto')
                            ->label('Foto')
                            ->image()
                            ->directory('pegawai/foto')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif']),
                    ])
                    ->columns(1),
            ]);
    }
}
