<?php

namespace App\Filament\Resources\TabelHubunganKeluargas\Pages;

use App\Filament\Resources\TabelHubunganKeluargas\TabelHubunganKeluargaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTabelHubunganKeluarga extends EditRecord
{
    protected static string $resource = TabelHubunganKeluargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
