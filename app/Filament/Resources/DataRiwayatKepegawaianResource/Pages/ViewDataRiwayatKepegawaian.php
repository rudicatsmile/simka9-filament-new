<?php

namespace App\Filament\Resources\DataRiwayatKepegawaianResource\Pages;

use App\Filament\Resources\DataRiwayatKepegawaianResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

/**
 * ViewDataRiwayatKepegawaian
 * 
 * Halaman untuk melihat detail data riwayat kepegawaian
 */
class ViewDataRiwayatKepegawaian extends ViewRecord
{
    protected static string $resource = DataRiwayatKepegawaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}