<?php

namespace App\Filament\Resources\CwspsRoleResource\Pages;

use App\Filament\Resources\CwspsRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCwspsRole extends EditRecord
{
    protected static string $resource = CwspsRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn () => auth()->user()?->hasPermission('roles.delete') ?? false),
        ];
    }
}