<?php

namespace App\Filament\Resources\DataAnaks\Pages;

use App\Filament\Resources\DataAnaks\DataAnakResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDataAnaks extends ListRecords
{
    protected static string $resource = DataAnakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn() => auth()->user()?->hasPermission('data-anaks.create') ?? false),
        ];
    }
}
