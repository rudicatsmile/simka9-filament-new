<?php

namespace App\Filament\Resources\DataRiwayatUnitKerjaLains;

use App\Filament\Resources\DataRiwayatUnitKerjaLains\Pages\CreateDataRiwayatUnitKerjaLain;
use App\Filament\Resources\DataRiwayatUnitKerjaLains\Pages\EditDataRiwayatUnitKerjaLain;
use App\Filament\Resources\DataRiwayatUnitKerjaLains\Pages\ListDataRiwayatUnitKerjaLains;
use App\Filament\Resources\DataRiwayatUnitKerjaLains\Schemas\DataRiwayatUnitKerjaLainForm;
use App\Filament\Resources\DataRiwayatUnitKerjaLains\Tables\DataRiwayatUnitKerjaLainsTable;
use App\Models\DataRiwayatUnitKerjaLain;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DataRiwayatUnitKerjaLainResource extends Resource
{
    protected static ?string $model = DataRiwayatUnitKerjaLain::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Riwayat Pegawai';

    protected static ?string $navigationLabel = 'Data Unit Kerja Lain';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return DataRiwayatUnitKerjaLainForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DataRiwayatUnitKerjaLainsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDataRiwayatUnitKerjaLains::route('/'),
            'create' => CreateDataRiwayatUnitKerjaLain::route('/create'),
            'edit' => EditDataRiwayatUnitKerjaLain::route('/{record}/edit'),
        ];
    }
}
