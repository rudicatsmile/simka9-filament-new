<?php

namespace App\Filament\Resources\TabelStatusKepegawaians;

use App\Filament\Resources\TabelStatusKepegawaians\Pages\CreateTabelStatusKepegawaian;
use App\Filament\Resources\TabelStatusKepegawaians\Pages\EditTabelStatusKepegawaian;
use App\Filament\Resources\TabelStatusKepegawaians\Pages\ListTabelStatusKepegawaians;
use App\Filament\Resources\TabelStatusKepegawaians\Schemas\TabelStatusKepegawaianForm;
use App\Filament\Resources\TabelStatusKepegawaians\Tables\TabelStatusKepegawaiansTable;
use App\Models\TabelStatusKepegawaian;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TabelStatusKepegawaianResource extends Resource
{
    protected static ?string $model = TabelStatusKepegawaian::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Status Kepegawaian';

    protected static ?string $modelLabel = 'Status Kepegawaian';

    protected static ?string $pluralModelLabel = 'Status Kepegawaian';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return TabelStatusKepegawaianForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TabelStatusKepegawaiansTable::configure($table);
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
            'index' => ListTabelStatusKepegawaians::route('/'),
            'create' => CreateTabelStatusKepegawaian::route('/create'),
            'edit' => EditTabelStatusKepegawaian::route('/{record}/edit'),
        ];
    }
}
