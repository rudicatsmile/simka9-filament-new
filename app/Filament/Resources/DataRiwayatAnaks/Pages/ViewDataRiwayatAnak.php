<?php

namespace App\Filament\Resources\DataRiwayatAnaks\Pages;

use App\Filament\Resources\DataRiwayatAnaks\DataRiwayatAnakResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDataRiwayatAnak extends ViewRecord
{
    protected static string $resource = DataRiwayatAnakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
