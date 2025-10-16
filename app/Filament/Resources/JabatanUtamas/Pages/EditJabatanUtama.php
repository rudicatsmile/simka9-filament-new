<?php

namespace App\Filament\Resources\JabatanUtamas\Pages;

use App\Filament\Resources\JabatanUtamas\JabatanUtamaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJabatanUtama extends EditRecord
{
    protected static string $resource = JabatanUtamaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn() => auth()->user()?->hasPermission('jabatan-utamas.delete') ?? false),
        ];
    }
}
