<?php

namespace App\Filament\Resources\DataRiwayatPasangans\Pages;

use App\Filament\Resources\DataRiwayatPasangans\DataRiwayatPasanganResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDataRiwayatPasangan extends EditRecord
{
    protected static string $resource = DataRiwayatPasanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
