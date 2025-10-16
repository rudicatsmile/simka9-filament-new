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
    /**
     * Resource yang digunakan oleh halaman ini
     * 
     * @var string
     */
    protected static string $resource = TabelPekerjaanResource::class;

    /**
     * Mendapatkan aksi header untuk halaman ini
     * 
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
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