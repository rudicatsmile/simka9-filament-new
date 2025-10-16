<?php

namespace App\Filament\Resources\JenjangPendidikan\Pages;

use App\Filament\Resources\JenjangPendidikan\JenjangPendidikanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJenjangPendidikan extends EditRecord
{
    protected static string $resource = JenjangPendidikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}