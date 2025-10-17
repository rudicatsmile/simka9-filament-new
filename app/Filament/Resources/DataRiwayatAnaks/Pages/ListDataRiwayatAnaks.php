<?php

namespace App\Filament\Resources\DataRiwayatAnaks\Pages;

use App\Filament\Resources\DataRiwayatAnaks\DataRiwayatAnakResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDataRiwayatAnaks extends ListRecords
{
    protected static string $resource = DataRiwayatAnakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
