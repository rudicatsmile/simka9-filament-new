<?php

namespace App\Filament\Resources\DataPendidikans\Pages;

use App\Filament\Resources\DataPendidikans\DataPendidikanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDataPendidikan extends EditRecord
{
    protected static string $resource = DataPendidikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
