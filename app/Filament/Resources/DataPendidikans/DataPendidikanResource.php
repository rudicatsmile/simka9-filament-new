<?php

namespace App\Filament\Resources\DataPendidikans;

use App\Filament\Resources\DataPendidikans\Pages\CreateDataPendidikan;
use App\Filament\Resources\DataPendidikans\Pages\EditDataPendidikan;
use App\Filament\Resources\DataPendidikans\Pages\ListDataPendidikans;
use App\Filament\Resources\DataPendidikans\Schemas\DataPendidikanForm;
use App\Filament\Resources\DataPendidikans\Tables\DataPendidikansTable;
use App\Models\DataPendidikan;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DataPendidikanResource extends Resource
{
    protected static ?string $model = DataPendidikan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|UnitEnum|null $navigationGroup = 'Riwayat Pegawai';

    protected static ?string $navigationLabel = 'Pendidikan';

    public static function form(Schema $schema): Schema
    {
        return DataPendidikanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DataPendidikansTable::configure($table);
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
            'index' => ListDataPendidikans::route('/'),
            'create' => CreateDataPendidikan::route('/create'),
            'edit' => EditDataPendidikan::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('data-pendidikans.view') ?? false;
    }

    public static function canViewAny(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('data-pendidikans.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('data-pendidikans.edit') ?? false;
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('data-pendidikans.delete') ?? false;
    }
}
