<?php

namespace App\Filament\Resources\DataRiwayatSertifikasis\Pages;

use App\Filament\Resources\DataRiwayatSertifikasis\DataRiwayatSertifikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDataRiwayatSertifikasi extends ViewRecord
{
    protected static string $resource = DataRiwayatSertifikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}