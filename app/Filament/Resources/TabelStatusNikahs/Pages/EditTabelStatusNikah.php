<?php

namespace App\Filament\Resources\TabelStatusNikahs\Pages;

use App\Filament\Resources\TabelStatusNikahs\TabelStatusNikahResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTabelStatusNikah extends EditRecord
{
    protected static string $resource = TabelStatusNikahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
