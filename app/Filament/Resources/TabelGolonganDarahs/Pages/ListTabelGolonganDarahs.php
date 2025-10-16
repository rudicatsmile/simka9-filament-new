<?php

namespace App\Filament\Resources\TabelGolonganDarahs\Pages;

use App\Filament\Resources\TabelGolonganDarahs\TabelGolonganDarahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTabelGolonganDarahs extends ListRecords
{
    protected static string $resource = TabelGolonganDarahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
