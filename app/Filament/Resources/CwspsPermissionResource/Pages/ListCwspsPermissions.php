<?php

namespace App\Filament\Resources\CwspsPermissionResource\Pages;

use App\Filament\Resources\CwspsPermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCwspsPermissions extends ListRecords
{
    protected static string $resource = CwspsPermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}