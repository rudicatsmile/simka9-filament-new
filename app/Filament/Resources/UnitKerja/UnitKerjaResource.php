<?php

namespace App\Filament\Resources\UnitKerja;

use App\Filament\Resources\UnitKerja\Pages\CreateUnitKerja;
use App\Filament\Resources\UnitKerja\Pages\EditUnitKerja;
use App\Filament\Resources\UnitKerja\Pages\ListUnitKerja;
use App\Filament\Resources\UnitKerja\Schemas\UnitKerjaForm;
use App\Filament\Resources\UnitKerja\Tables\UnitKerjaTable;
use App\Models\UnitKerja;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UnitKerjaResource extends Resource
{
    protected static ?string $model = UnitKerja::class;

    protected static ?string $navigationLabel = 'Unit Kerja';
    
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-building-office';

    public static function form(Schema $schema): Schema
    {
        return UnitKerjaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitKerjaTable::configure($table);
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
            'index' => ListUnitKerja::route('/'),
            // 'create' => CreateUnitKerja::route('/create'), // Commented out - using modal instead
            // 'edit' => EditUnitKerja::route('/{record}/edit'), // Commented out - using modal instead
        ];
    }
}