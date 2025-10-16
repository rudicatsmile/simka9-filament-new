<?php

namespace App\Filament\Resources\UnitKerja\Pages;

use App\Filament\Resources\UnitKerja\UnitKerjaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUnitKerja extends EditRecord
{
    protected static string $resource = UnitKerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn () => auth()->user()?->hasPermission('unit-kerja.delete') ?? false),
        ];
    }
}