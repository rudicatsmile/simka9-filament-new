<?php

namespace App\Filament\Resources\Agamas;

use App\Filament\Resources\Agamas\Pages\CreateAgama;
use App\Filament\Resources\Agamas\Pages\EditAgama;
use App\Filament\Resources\Agamas\Pages\ListAgamas;
use App\Filament\Resources\Agamas\Schemas\AgamaForm;
use App\Filament\Resources\Agamas\Tables\AgamasTable;
use App\Models\Agama;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AgamaResource extends Resource
{
    protected static ?string $model = Agama::class;

    protected static ?string $navigationLabel = 'Agama';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = '';

    protected static ?string $pluralModelLabel = 'Data Agama';



    public static function form(Schema $schema): Schema
    {
        return AgamaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AgamasTable::configure($table);
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
            'index' => ListAgamas::route('/'),
            // 'create' => CreateAgama::route('/create'), // Commented out - using modal instead
            // 'edit' => EditAgama::route('/{record}/edit'), // Commented out - using modal instead
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('agamas.view') ?? false;
    }

    public static function canViewAny(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('agamas.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('agamas.edit') ?? false;
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('agamas.delete') ?? false;
    }
}
