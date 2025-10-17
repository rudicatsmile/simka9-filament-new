<?php

namespace App\Filament\Resources\DataRiwayatKepegawaianResource\Pages;

use App\Filament\Resources\DataRiwayatKepegawaianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * ListDataRiwayatKepegawaians
 * 
 * Halaman untuk menampilkan daftar data riwayat kepegawaian
 */
class ListDataRiwayatKepegawaians extends ListRecords
{
    protected static string $resource = DataRiwayatKepegawaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}