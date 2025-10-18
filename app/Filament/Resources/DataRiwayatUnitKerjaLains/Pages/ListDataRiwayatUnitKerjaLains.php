<?php

namespace App\Filament\Resources\DataRiwayatUnitKerjaLains\Pages;

use App\Filament\Resources\DataRiwayatUnitKerjaLains\DataRiwayatUnitKerjaLainResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDataRiwayatUnitKerjaLains extends ListRecords
{
    protected static string $resource = DataRiwayatUnitKerjaLainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
