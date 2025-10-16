<?php

namespace App\Filament\Resources\DataPasangans;

use App\Filament\Resources\DataPasangans\Pages\CreateDataPasangan;
use App\Filament\Resources\DataPasangans\Pages\EditDataPasangan;
use App\Filament\Resources\DataPasangans\Pages\ListDataPasangans;
use App\Filament\Resources\DataPasangans\Schemas\DataPasanganForm;
use App\Filament\Resources\DataPasangans\Tables\DataPasangansTable;
use App\Models\DataPasangan;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DataPasanganResource extends Resource
{
    protected static ?string $model = DataPasangan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    protected static string|UnitEnum|null $navigationGroup = 'Riwayat Pegawai';

    protected static ?string $navigationLabel = 'Data Pasangan';

    public static function form(Schema $schema): Schema
    {
        return DataPasanganForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DataPasangansTable::configure($table);
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
            'index' => ListDataPasangans::route('/'),
            'create' => CreateDataPasangan::route('/create'),
            'edit' => EditDataPasangan::route('/{record}/edit'),
        ];
    }
}
