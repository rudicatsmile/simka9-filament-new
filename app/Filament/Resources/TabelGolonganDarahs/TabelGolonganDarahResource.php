<?php

namespace App\Filament\Resources\TabelGolonganDarahs;

use App\Filament\Resources\TabelGolonganDarahs\Pages\CreateTabelGolonganDarah;
use App\Filament\Resources\TabelGolonganDarahs\Pages\EditTabelGolonganDarah;
use App\Filament\Resources\TabelGolonganDarahs\Pages\ListTabelGolonganDarahs;
use App\Filament\Resources\TabelGolonganDarahs\Schemas\TabelGolonganDarahForm;
use App\Filament\Resources\TabelGolonganDarahs\Tables\TabelGolonganDarahsTable;
use App\Models\TabelGolonganDarah;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TabelGolonganDarahResource extends Resource
{
    protected static ?string $model = TabelGolonganDarah::class;

    protected static ?string $navigationLabel = 'Golongan Darah';
    
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return TabelGolonganDarahForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TabelGolonganDarahsTable::configure($table);
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
            'index' => ListTabelGolonganDarahs::route('/'),
            // 'create' => CreateTabelGolonganDarah::route('/create'), // Commented out - using modal instead
            // 'edit' => EditTabelGolonganDarah::route('/{record}/edit'), // Commented out - using modal instead
        ];
    }
}
