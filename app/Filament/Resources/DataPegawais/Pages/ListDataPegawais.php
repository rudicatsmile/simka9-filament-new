<?php

namespace App\Filament\Resources\DataPegawais\Pages;

use App\Filament\Resources\DataPegawais\DataPegawaiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDataPegawais extends ListRecords
{
    protected static string $resource = DataPegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
