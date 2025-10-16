<?php

namespace App\Filament\Resources\TabelStatusKepegawaians\Pages;

use App\Filament\Resources\TabelStatusKepegawaians\TabelStatusKepegawaianResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTabelStatusKepegawaians extends ListRecords
{
    protected static string $resource = TabelStatusKepegawaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn () => auth()->user()?->hasPermission('tabel-status-kepegawaians.create') ?? false),
        ];
    }
}
