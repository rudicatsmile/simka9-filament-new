<?php

namespace App\Filament\Resources\TabelHubunganKeluargas;

use App\Filament\Resources\TabelHubunganKeluargas\Pages\CreateTabelHubunganKeluarga;
use App\Filament\Resources\TabelHubunganKeluargas\Pages\EditTabelHubunganKeluarga;
use App\Filament\Resources\TabelHubunganKeluargas\Pages\ListTabelHubunganKeluargas;
use App\Filament\Resources\TabelHubunganKeluargas\Schemas\TabelHubunganKeluargaForm;
use App\Filament\Resources\TabelHubunganKeluargas\Tables\TabelHubunganKeluargasTable;
use App\Models\TabelHubunganKeluarga;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TabelHubunganKeluargaResource extends Resource
{
    protected static ?string $model = TabelHubunganKeluarga::class;

    protected static ?string $navigationLabel = 'Hubungan Keluarga';
    
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return TabelHubunganKeluargaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TabelHubunganKeluargasTable::configure($table);
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
            'index' => ListTabelHubunganKeluargas::route('/'),
            // 'create' => CreateTabelHubunganKeluarga::route('/create'), // Commented out - using modal instead
            // 'edit' => EditTabelHubunganKeluarga::route('/{record}/edit'), // Commented out - using modal instead
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('tabel-hubungan-keluargas.view') ?? false;
    }

    public static function canViewAny(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('tabel-hubungan-keluargas.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('tabel-hubungan-keluargas.edit') ?? false;
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('tabel-hubungan-keluargas.delete') ?? false;
    }
}
