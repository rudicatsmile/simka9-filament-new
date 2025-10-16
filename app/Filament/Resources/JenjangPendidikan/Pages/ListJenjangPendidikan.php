<?php

namespace App\Filament\Resources\JenjangPendidikan\Pages;

use App\Filament\Resources\JenjangPendidikan\JenjangPendidikanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJenjangPendidikan extends ListRecords
{
    protected static string $resource = JenjangPendidikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Jenjang Pendidikan')
                ->icon('heroicon-m-plus')
                ->color('primary')
                ->form(fn ($form) => JenjangPendidikanResource::form($form))
                ->modalHeading('Add New Jenjang Pendidikan')
                ->modalSubmitActionLabel('Save Jenjang Pendidikan')
                ->modalCancelActionLabel('Cancel')
                ->successNotificationTitle('Jenjang Pendidikan created successfully!')
                ->after(fn () => redirect()->to(JenjangPendidikanResource::getUrl('index'))),
        ];
    }
}