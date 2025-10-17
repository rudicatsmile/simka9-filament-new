<?php

namespace App\Filament\Resources\DataRiwayatSertifikasis\Pages;

use App\Filament\Resources\DataRiwayatSertifikasis\DataRiwayatSertifikasiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDataRiwayatSertifikasi extends EditRecord
{
    protected static string $resource = DataRiwayatSertifikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
