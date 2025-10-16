<?php

namespace App\Filament\Resources\DataPasangans\Pages;

use App\Filament\Resources\DataPasangans\DataPasanganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDataPasangans extends ListRecords
{
    protected static string $resource = DataPasanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
