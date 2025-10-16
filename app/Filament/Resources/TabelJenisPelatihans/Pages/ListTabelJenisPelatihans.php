<?php

namespace App\Filament\Resources\TabelJenisPelatihans\Pages;

use App\Filament\Resources\TabelJenisPelatihans\TabelJenisPelatihanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

/**
 * ListTabelJenisPelatihans
 * 
 * Halaman untuk menampilkan daftar jenis pelatihan.
 * Menyediakan aksi untuk menambah data jenis pelatihan baru.
 * 
 * @package App\Filament\Resources\TabelJenisPelatihans\Pages
 * @author Laravel Filament
 * @version 1.0.0
 */
class ListTabelJenisPelatihans extends ListRecords
{
    /**
     * Resource yang digunakan oleh halaman ini
     * 
     * @var string
     */
    protected static string $resource = TabelJenisPelatihanResource::class;

    /**
     * Mendapatkan aksi header untuk halaman ini
     * 
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Jenis Pelatihan')
                ->icon('heroicon-m-plus')
                ->color('primary')
                ->form(fn ($form) => TabelJenisPelatihanResource::form($form))
                ->modalHeading('Add New Jenis Pelatihan')
                ->modalSubmitActionLabel('Save Jenis Pelatihan')
                ->modalCancelActionLabel('Cancel')
                ->successNotificationTitle('Jenis Pelatihan created successfully!')
                ->after(fn () => redirect()->to(TabelJenisPelatihanResource::getUrl('index'))),
        ];
    }
}