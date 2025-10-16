<?php

namespace App\Filament\Resources\CwspsRoleResource\Pages;

use App\Filament\Resources\CwspsRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCwspsRoles extends ListRecords
{
    protected static string $resource = CwspsRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}