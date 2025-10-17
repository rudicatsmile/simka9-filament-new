<?php

namespace App\Filament\Resources\DataRiwayatPelatihans\Pages;

use App\Filament\Resources\DataRiwayatPelatihans\DataRiwayatPelatihanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDataRiwayatPelatihan extends EditRecord
{
    protected static string $resource = DataRiwayatPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
