<?php

namespace App\Filament\Resources\Agamas\Pages;

use App\Filament\Resources\Agamas\AgamaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAgamas extends ListRecords
{
    protected static string $resource = AgamaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Agama')
                ->icon('heroicon-m-plus')
                ->color('primary')
                ->form(fn ($form) => AgamaResource::form($form))
                ->modalHeading('Add New Agama')
                ->modalSubmitActionLabel('Save Agama')
                ->modalCancelActionLabel('Cancel')
                ->successNotificationTitle('Agama created successfully!')
                ->after(fn () => redirect()->to(AgamaResource::getUrl('index'))),
        ];
    }
}
