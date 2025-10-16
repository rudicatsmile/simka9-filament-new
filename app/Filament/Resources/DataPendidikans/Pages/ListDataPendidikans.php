<?php

namespace App\Filament\Resources\DataPendidikans\Pages;

use App\Filament\Resources\DataPendidikans\DataPendidikanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDataPendidikans extends ListRecords
{
    protected static string $resource = DataPendidikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
