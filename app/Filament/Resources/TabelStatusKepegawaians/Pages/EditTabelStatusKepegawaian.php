<?php

namespace App\Filament\Resources\TabelStatusKepegawaians\Pages;

use App\Filament\Resources\TabelStatusKepegawaians\TabelStatusKepegawaianResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTabelStatusKepegawaian extends EditRecord
{
    protected static string $resource = TabelStatusKepegawaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn () => auth()->user()?->hasPermission('tabel-status-kepegawaians.delete') ?? false),
        ];
    }
}
