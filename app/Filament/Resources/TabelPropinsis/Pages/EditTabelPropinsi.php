<?php

namespace App\Filament\Resources\TabelPropinsis\Pages;

use App\Filament\Resources\TabelPropinsis\TabelPropinsiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTabelPropinsi extends EditRecord
{
    protected static string $resource = TabelPropinsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn () => auth()->user()?->hasPermission('tabel-propinsis.delete') ?? false),
        ];
    }
}
