<?php

namespace App\Filament\Resources\DataRiwayatPelatihans\Pages;

use App\Filament\Resources\DataRiwayatPelatihans\DataRiwayatPelatihanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDataRiwayatPelatihans extends ListRecords
{
    protected static string $resource = DataRiwayatPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
