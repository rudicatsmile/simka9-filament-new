<?php

namespace App\Filament\Resources\DataRiwayatPasangans\Pages;

use App\Filament\Resources\DataRiwayatPasangans\DataRiwayatPasanganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDataRiwayatPasangans extends ListRecords
{
    protected static string $resource = DataRiwayatPasanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
