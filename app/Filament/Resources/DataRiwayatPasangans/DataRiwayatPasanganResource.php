<?php

namespace App\Filament\Resources\DataRiwayatPasangans;

use App\Filament\Resources\DataRiwayatPasangans\Pages\CreateDataRiwayatPasangan;
use App\Filament\Resources\DataRiwayatPasangans\Pages\EditDataRiwayatPasangan;
use App\Filament\Resources\DataRiwayatPasangans\Pages\ListDataRiwayatPasangans;
use App\Filament\Resources\DataRiwayatPasangans\Schemas\DataRiwayatPasanganForm;
use App\Filament\Resources\DataRiwayatPasangans\Tables\DataRiwayatPasangansTable;
use App\Models\DataRiwayatPasangan;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DataRiwayatPasanganResource extends Resource
{
    protected static ?string $model = DataRiwayatPasangan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Riwayat Pegawai';

    protected static ?string $navigationLabel = 'Data Riwayat Pasangan';

    public static function form(Schema $schema): Schema
    {
        return DataRiwayatPasanganForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DataRiwayatPasangansTable::configure($table);
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
            'index' => ListDataRiwayatPasangans::route('/'),
            'create' => CreateDataRiwayatPasangan::route('/create'),
            'edit' => EditDataRiwayatPasangan::route('/{record}/edit'),
        ];
    }
}
