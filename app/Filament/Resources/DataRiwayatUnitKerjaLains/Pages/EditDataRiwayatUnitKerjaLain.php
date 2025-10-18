<?php

namespace App\Filament\Resources\DataRiwayatUnitKerjaLains\Pages;

use App\Filament\Resources\DataRiwayatUnitKerjaLains\DataRiwayatUnitKerjaLainResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDataRiwayatUnitKerjaLain extends EditRecord
{
    protected static string $resource = DataRiwayatUnitKerjaLainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
