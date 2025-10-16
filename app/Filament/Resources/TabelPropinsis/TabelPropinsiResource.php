<?php

namespace App\Filament\Resources\TabelPropinsis;

use App\Filament\Resources\TabelPropinsis\Pages\CreateTabelPropinsi;
use App\Filament\Resources\TabelPropinsis\Pages\EditTabelPropinsi;
use App\Filament\Resources\TabelPropinsis\Pages\ListTabelPropinsis;
use App\Filament\Resources\TabelPropinsis\Schemas\TabelPropinsiForm;
use App\Filament\Resources\TabelPropinsis\Tables\TabelPropinsisTable;
use App\Models\TabelPropinsi;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TabelPropinsiResource extends Resource
{
    protected static ?string $model = TabelPropinsi::class;

    protected static ?string $navigationLabel = 'Propinsi';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map';

    public static function form(Schema $schema): Schema
    {
        return TabelPropinsiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TabelPropinsisTable::configure($table);
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
            'index' => ListTabelPropinsis::route('/'),
            // 'create' => CreateTabelPropinsi::route('/create'), // Commented out - using modal instead
            // 'edit' => EditTabelPropinsi::route('/{record}/edit'), // Commented out - using modal instead
        ];
    }
}
