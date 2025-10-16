<?php

namespace App\Filament\Resources\TabelPekerjaans;

use App\Filament\Resources\TabelPekerjaans\Pages\CreateTabelPekerjaan;
use App\Filament\Resources\TabelPekerjaans\Pages\EditTabelPekerjaan;
use App\Filament\Resources\TabelPekerjaans\Pages\ListTabelPekerjaans;
use App\Filament\Resources\TabelPekerjaans\Schemas\TabelPekerjaanForm;
use App\Filament\Resources\TabelPekerjaans\Tables\TabelPekerjaansTable;
use App\Models\TabelPekerjaan;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * TabelPekerjaanResource
 * 
 * Filament Resource untuk mengelola data pekerjaan.
 * Menyediakan interface CRUD untuk tabel_pekerjaan.
 * 
 * @package App\Filament\Resources\TabelPekerjaans
 * @author Laravel Filament
 * @version 1.0.0
 */
class TabelPekerjaanResource extends Resource
{
    /**
     * Model yang digunakan oleh resource ini
     * 
     * @var string
     */
    protected static ?string $model = TabelPekerjaan::class;

    /**
     * Label navigasi untuk resource ini
     * 
     * @var string
     */
    protected static ?string $navigationLabel = 'Pekerjaan';
    
    /**
     * Grup navigasi untuk resource ini
     * 
     * @var string|UnitEnum|null
     */
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    /**
     * Icon navigasi untuk resource ini
     * 
     * @var string|BackedEnum|null
     */
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Konfigurasi form untuk resource ini
     * 
     * @param Schema $schema
     * @return Schema
     */
    public static function form(Schema $schema): Schema
    {
        return TabelPekerjaanForm::configure($schema);
    }

    /**
     * Konfigurasi table untuk resource ini
     * 
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return TabelPekerjaansTable::configure($table);
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
     * Mendapatkan halaman yang tersedia untuk resource ini
     * 
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => ListTabelPekerjaans::route('/'),
            // 'create' => CreateTabelPekerjaan::route('/create'), // Commented out - using modal instead
            // 'edit' => EditTabelPekerjaan::route('/{record}/edit'), // Commented out - using modal instead
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('tabel-pekerjaan.view') ?? false;
    }

    public static function canViewAny(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('tabel-pekerjaan.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('tabel-pekerjaan.edit') ?? false;
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('tabel-pekerjaan.delete') ?? false;
    }
}