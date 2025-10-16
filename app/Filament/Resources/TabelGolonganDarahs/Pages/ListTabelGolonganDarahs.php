<?php

namespace App\Filament\Resources\TabelGolonganDarahs\Pages;

use App\Filament\Resources\TabelGolonganDarahs\TabelGolonganDarahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTabelGolonganDarahs extends ListRecords
{
    protected static string $resource = TabelGolonganDarahResource::class;

    protected static ?string $title = 'Golongan Darah';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn() => auth()->user()?->hasPermission('tabel-golongan-darah.create') ?? false)
                ->label('Tambah Golongan Darah')
                ->icon('heroicon-m-plus')
                ->color('primary')
                ->modalHeading('Tambah Golongan Darah')
                ->modalSubmitActionLabel('Save')
                ->modalCancelActionLabel('Cancel')
                ->successNotificationTitle('Tabel Golongan Darah created successfully!')
                ->after(fn() => redirect()->to(TabelGolonganDarahResource::getUrl('index'))),
        ];
    }
}
