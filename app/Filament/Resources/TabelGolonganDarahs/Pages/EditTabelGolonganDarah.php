<?php

namespace App\Filament\Resources\TabelGolonganDarahs\Pages;

use App\Filament\Resources\TabelGolonganDarahs\TabelGolonganDarahResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTabelGolonganDarah extends EditRecord
{
    protected static string $resource = TabelGolonganDarahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
