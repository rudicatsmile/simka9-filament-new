<?php

namespace App\Filament\Resources\TabelPekerjaans\Pages;

use App\Filament\Resources\TabelPekerjaans\TabelPekerjaanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

/**
 * ListTabelPekerjaans
 * 
 * Halaman untuk menampilkan daftar pekerjaan.
 * Menyediakan aksi untuk menambah data pekerjaan baru.
 * 
 * @package App\Filament\Resources\TabelPekerjaans\Pages
 * @author Laravel Filament
 * @version 1.0.0
 */
class ListTabelPekerjaans extends ListRecords
{
    protected static string $resource = TabelPekerjaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn () => auth()->user()?->hasPermission('tabel-pekerjaan.create') ?? false)
                ->label('Add Pekerjaan')
                ->icon('heroicon-m-plus')
                ->color('primary')
                ->form(fn ($form) => TabelPekerjaanResource::form($form))
                ->modalHeading('Add New Pekerjaan')
                ->modalSubmitActionLabel('Save Pekerjaan')
                ->modalCancelActionLabel('Cancel')
                ->successNotificationTitle('Pekerjaan created successfully!')
                ->after(fn () => redirect()->to(TabelPekerjaanResource::getUrl('index'))),
        ];
    }
}