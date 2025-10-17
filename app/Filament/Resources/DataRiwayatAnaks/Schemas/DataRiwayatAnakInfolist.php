<?php

namespace App\Filament\Resources\DataRiwayatAnaks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DataRiwayatAnakInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nik_data_pegawai'),
                TextEntry::make('nama_anak')
                    ->placeholder('-'),
                TextEntry::make('tempat_lahir')
                    ->placeholder('-'),
                TextEntry::make('tanggal_lahir')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('jenis_kelamin')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('kode_tabel_hubungan_keluarga')
                    ->placeholder('-'),
                TextEntry::make('kode_jenjang_pendidikan')
                    ->placeholder('-'),
                TextEntry::make('kode_tabel_pekerjaan')
                    ->placeholder('-'),
                TextEntry::make('urut')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
