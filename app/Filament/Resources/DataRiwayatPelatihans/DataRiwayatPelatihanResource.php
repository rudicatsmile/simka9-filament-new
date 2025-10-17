<?php

namespace App\Filament\Resources\DataRiwayatPelatihans;

use App\Filament\Resources\DataRiwayatPelatihans\Pages\CreateDataRiwayatPelatihan;
use App\Filament\Resources\DataRiwayatPelatihans\Pages\EditDataRiwayatPelatihan;
use App\Filament\Resources\DataRiwayatPelatihans\Pages\ListDataRiwayatPelatihans;
use App\Filament\Resources\DataRiwayatPelatihans\Schemas\DataRiwayatPelatihanForm;
use App\Filament\Resources\DataRiwayatPelatihans\Tables\DataRiwayatPelatihansTable;
use App\Models\DataRiwayatPelatihan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class DataRiwayatPelatihanResource extends Resource
{
    protected static ?string $model = DataRiwayatPelatihan::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Data Pelatihan';

    protected static ?string $modelLabel = 'Data Pelatihan';

    protected static ?string $pluralModelLabel = 'Data Pelatihan';

    protected static string|UnitEnum|null $navigationGroup = 'Data Pegawai';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return DataRiwayatPelatihanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DataRiwayatPelatihansTable::configure($table);
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
            'index' => ListDataRiwayatPelatihans::route('/'),
            'create' => CreateDataRiwayatPelatihan::route('/create'),
            'edit' => EditDataRiwayatPelatihan::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermission('data-riwayat-pelatihan.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermission('data-riwayat-pelatihan.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->hasPermission('data-riwayat-pelatihan.edit') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasPermission('data-riwayat-pelatihan.delete') ?? false;
    }
}
