<?php

namespace App\Filament\Resources\JenjangPendidikan;

use App\Filament\Resources\JenjangPendidikan\Pages\CreateJenjangPendidikan;
use App\Filament\Resources\JenjangPendidikan\Pages\EditJenjangPendidikan;
use App\Filament\Resources\JenjangPendidikan\Pages\ListJenjangPendidikan;
use App\Filament\Resources\JenjangPendidikan\Schemas\JenjangPendidikanForm;
use App\Filament\Resources\JenjangPendidikan\Tables\JenjangPendidikanTable;
use App\Models\JenjangPendidikan;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JenjangPendidikanResource extends Resource
{
    protected static ?string $model = JenjangPendidikan::class;

    protected static ?string $navigationLabel = 'Jenjang Pendidikan';
    
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Schema $schema): Schema
    {
        return JenjangPendidikanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JenjangPendidikanTable::configure($table);
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
            'index' => ListJenjangPendidikan::route('/'),
            // 'create' => CreateJenjangPendidikan::route('/create'), // Commented out - using modal instead
            // 'edit' => EditJenjangPendidikan::route('/{record}/edit'), // Commented out - using modal instead
        ];
    }
}