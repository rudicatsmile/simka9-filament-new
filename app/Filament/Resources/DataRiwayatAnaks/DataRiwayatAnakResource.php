<?php

namespace App\Filament\Resources\DataRiwayatAnaks;

use App\Filament\Resources\DataRiwayatAnaks\Pages\CreateDataRiwayatAnak;
use App\Filament\Resources\DataRiwayatAnaks\Pages\EditDataRiwayatAnak;
use App\Filament\Resources\DataRiwayatAnaks\Pages\ListDataRiwayatAnaks;
use App\Filament\Resources\DataRiwayatAnaks\Pages\ViewDataRiwayatAnak;
use App\Filament\Resources\DataRiwayatAnaks\Schemas\DataRiwayatAnakForm;
use App\Filament\Resources\DataRiwayatAnaks\Schemas\DataRiwayatAnakInfolist;
use App\Filament\Resources\DataRiwayatAnaks\Tables\DataRiwayatAnaksTable;
use App\Models\DataRiwayatAnak;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class DataRiwayatAnakResource extends Resource
{
    protected static ?string $model = DataRiwayatAnak::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|UnitEnum|null $navigationGroup = 'Riwayat Pegawai';

    protected static ?string $navigationLabel = 'Data Anak';

    protected static ?string $modelLabel = 'Data Anak';

    protected static ?string $pluralModelLabel = 'Data Anak';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return DataRiwayatAnakForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DataRiwayatAnakInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DataRiwayatAnaksTable::configure($table);
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
            'index' => ListDataRiwayatAnaks::route('/'),
            'create' => CreateDataRiwayatAnak::route('/create'),
            'view' => ViewDataRiwayatAnak::route('/{record}'),
            'edit' => EditDataRiwayatAnak::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
