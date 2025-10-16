<?php

namespace App\Filament\Resources\DataAnaks\Pages;

use App\Filament\Resources\DataAnaks\DataAnakResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDataAnak extends EditRecord
{
    protected static string $resource = DataAnakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
