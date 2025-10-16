<?php

namespace App\Filament\Resources\JabatanUtamas;

use App\Filament\Resources\JabatanUtamas\Pages\CreateJabatanUtama;
use App\Filament\Resources\JabatanUtamas\Pages\EditJabatanUtama;
use App\Filament\Resources\JabatanUtamas\Pages\ListJabatanUtamas;
use App\Filament\Resources\JabatanUtamas\Schemas\JabatanUtamaForm;
use App\Filament\Resources\JabatanUtamas\Tables\JabatanUtamasTable;
use App\Models\JabatanUtama;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * Filament Resource untuk JabatanUtama
 *
 * Resource ini mengelola CRUD operations untuk model JabatanUtama
 * dalam admin panel Filament
 */
class JabatanUtamaResource extends Resource
{
    protected static ?string $model = JabatanUtama::class;

    protected static ?string $navigationLabel = 'Jabatan Utama';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    /**
     * Konfigurasi form untuk resource
     *
     * @param Schema $schema
     * @return Schema
     */
    public static function form(Schema $schema): Schema
    {
        return JabatanUtamaForm::configure($schema);
    }

    /**
     * Konfigurasi table untuk resource
     *
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return JabatanUtamasTable::configure($table);
    }

    /**
     * Mendapatkan relasi yang tersedia
     *
     * @return array
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Mendapatkan halaman yang tersedia untuk resource
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => ListJabatanUtamas::route('/'),
            // 'create' => CreateJabatanUtama::route('/create'), // Commented out - using modal instead
            // 'edit' => EditJabatanUtama::route('/{record}/edit'), // Commented out - using modal instead
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('jabatan-utamas.view') ?? false;
    }

    public static function canViewAny(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();  //jabatan-utamass.create
        return $user?->hasPermission('jabatan-utamas.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('jabatan-utamas.edit') ?? false;
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('jabatan-utamas.delete') ?? false;
    }
}
