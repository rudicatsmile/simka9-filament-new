<?php

namespace App\Filament\Resources\CwspsPermissionResource\Pages;

use App\Filament\Resources\CwspsPermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCwspsPermission extends EditRecord
{
    protected static string $resource = CwspsPermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}