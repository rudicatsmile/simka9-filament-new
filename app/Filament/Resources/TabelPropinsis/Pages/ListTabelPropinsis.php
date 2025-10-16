<?php

namespace App\Filament\Resources\TabelPropinsis\Pages;

use App\Filament\Resources\TabelPropinsis\TabelPropinsiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTabelPropinsis extends ListRecords
{
    protected static string $resource = TabelPropinsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
