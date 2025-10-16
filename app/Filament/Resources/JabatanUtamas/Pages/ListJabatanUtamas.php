<?php

namespace App\Filament\Resources\JabatanUtamas\Pages;

use App\Filament\Resources\JabatanUtamas\JabatanUtamaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

/**
 * List page untuk JabatanUtama
 *
 * Halaman ini menampilkan daftar jabatan utama dan menyediakan
 * action untuk menambah data baru
 */
class ListJabatanUtamas extends ListRecords
{
    protected static string $resource = JabatanUtamaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn() => auth()->user()?->hasPermission('jabatan-utamas.create') ?? false)
                ->label('Add Jabatan Utama')
                ->icon('heroicon-m-plus')
                ->color('primary')
                ->modalHeading('Add New Jabatan Utama')
                ->modalSubmitActionLabel('Save')
                ->modalCancelActionLabel('Cancel')
                ->successNotificationTitle('Jabatan Utama created successfully!')
                ->after(fn() => redirect()->to(JabatanUtamaResource::getUrl('index'))),
        ];
    }
}
