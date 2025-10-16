<?php

namespace App\Filament\Resources\TabelHubunganKeluargas\Pages;

use App\Filament\Resources\TabelHubunganKeluargas\TabelHubunganKeluargaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTabelHubunganKeluargas extends ListRecords
{
    protected static string $resource = TabelHubunganKeluargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn () => auth()->user()?->hasPermission('tabel-hubungan-keluargas.create') ?? false)
                ->label('Add Hubungan Keluarga')
                ->icon('heroicon-m-plus')
                ->color('primary')
                ->form(fn ($form) => TabelHubunganKeluargaResource::form($form))
                ->modalHeading('Add New Hubungan Keluarga')
                ->modalSubmitActionLabel('Save Hubungan Keluarga')
                ->modalCancelActionLabel('Cancel')
                ->successNotificationTitle('Hubungan keluarga created successfully!')
                ->after(fn () => redirect()->to(TabelHubunganKeluargaResource::getUrl('index'))),
        ];
    }
}
