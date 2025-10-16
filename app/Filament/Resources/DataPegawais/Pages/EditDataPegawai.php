<?php

namespace App\Filament\Resources\DataPegawais\Pages;

use App\Filament\Resources\DataPegawais\DataPegawaiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDataPegawai extends EditRecord
{
    protected static string $resource = DataPegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
