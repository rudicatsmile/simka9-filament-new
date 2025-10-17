<?php

namespace App\Filament\Resources\DataRiwayatKepegawaianResource\Pages;

use App\Filament\Resources\DataRiwayatKepegawaianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * EditDataRiwayatKepegawaian
 * 
 * Halaman untuk mengedit data riwayat kepegawaian
 */
class EditDataRiwayatKepegawaian extends EditRecord
{
    protected static string $resource = DataRiwayatKepegawaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}