<?php

namespace App\Filament\Resources\TabelStatusNikahs;

use App\Filament\Resources\TabelStatusNikahs\Pages\CreateTabelStatusNikah;
use App\Filament\Resources\TabelStatusNikahs\Pages\EditTabelStatusNikah;
use App\Filament\Resources\TabelStatusNikahs\Pages\ListTabelStatusNikahs;
use App\Filament\Resources\TabelStatusNikahs\Schemas\TabelStatusNikahForm;
use App\Filament\Resources\TabelStatusNikahs\Tables\TabelStatusNikahsTable;
use App\Models\TabelStatusNikah;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * TabelStatusNikahResource
 * 
 * Filament Resource untuk mengelola data status nikah.
 * Menyediakan interface CRUD untuk tabel_status_nikah.
 * 
 * @package App\Filament\Resources\TabelStatusNikahs
 * @author Laravel Filament
 * @version 1.0.0
 */
class TabelStatusNikahResource extends Resource
{
    /**
     * Model yang digunakan oleh resource ini
     * 
     * @var string
     */
    protected static ?string $model = TabelStatusNikah::class;

    /**
     * Label navigasi untuk resource ini
     * 
     * @var string
     */
    protected static ?string $navigationLabel = 'Status Nikah';
    
    /**
     * Grup navigasi untuk resource ini
     * 
     * @var string
     */
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    /**
     * Icon navigasi untuk resource ini
     * 
     * @var string
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
        return TabelStatusNikahForm::configure($schema);
    }

    /**
     * Konfigurasi table untuk resource ini
     * 
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return TabelStatusNikahsTable::configure($table);
    }

    /**
     * Mendapatkan relasi untuk resource ini
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
     * Mendapatkan halaman untuk resource ini
     * 
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => ListTabelStatusNikahs::route('/'),
            // 'create' => CreateTabelStatusNikah::route('/create'), // Commented out - using modal instead
            // 'edit' => EditTabelStatusNikah::route('/{record}/edit'), // Commented out - using modal instead
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('tabel-status-nikahs.view') ?? false;
    }

    public static function canViewAny(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('tabel-status-nikahs.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('tabel-status-nikahs.edit') ?? false;
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('tabel-status-nikahs.delete') ?? false;
    }
}
