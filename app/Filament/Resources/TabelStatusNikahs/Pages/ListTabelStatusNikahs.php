<?php

namespace App\Filament\Resources\TabelStatusNikahs\Pages;

use App\Filament\Resources\TabelStatusNikahs\TabelStatusNikahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTabelStatusNikahs extends ListRecords
{
    protected static string $resource = TabelStatusNikahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn () => auth()->user()?->hasPermission('tabel-status-nikahs.create') ?? false),
        ];
    }
}
