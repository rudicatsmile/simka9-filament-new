<?php

namespace App\Filament\Resources\DataPasangans\Pages;

use App\Filament\Resources\DataPasangans\DataPasanganResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDataPasangan extends EditRecord
{
    protected static string $resource = DataPasanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn () => auth()->user()?->hasPermission('data-pasangans.delete') ?? false),
        ];
    }
}
