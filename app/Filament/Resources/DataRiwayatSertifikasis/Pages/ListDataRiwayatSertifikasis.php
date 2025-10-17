<?php

namespace App\Filament\Resources\DataRiwayatSertifikasis\Pages;

use App\Filament\Resources\DataRiwayatSertifikasis\DataRiwayatSertifikasiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDataRiwayatSertifikasis extends ListRecords
{
    protected static string $resource = DataRiwayatSertifikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
