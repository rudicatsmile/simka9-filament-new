<?php

namespace App\Filament\Resources\UnitKerja\Pages;

use App\Filament\Resources\UnitKerja\UnitKerjaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUnitKerja extends ListRecords
{
    protected static string $resource = UnitKerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Unit Kerja')
                ->icon('heroicon-m-plus')
                ->color('primary')
                ->modalHeading('Add New Unit Kerja')
                ->modalSubmitActionLabel('Save')
                ->modalCancelActionLabel('Cancel')
                ->successNotificationTitle('Unit Kerja created successfully!')
                ->after(fn () => redirect()->to(UnitKerjaResource::getUrl('index'))),
        ];
    }
}