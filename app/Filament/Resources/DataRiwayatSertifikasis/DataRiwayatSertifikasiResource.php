<?php

namespace App\Filament\Resources\DataRiwayatSertifikasis;

use App\Filament\Resources\DataRiwayatSertifikasis\Pages\CreateDataRiwayatSertifikasi;
use App\Filament\Resources\DataRiwayatSertifikasis\Pages\EditDataRiwayatSertifikasi;
use App\Filament\Resources\DataRiwayatSertifikasis\Pages\ListDataRiwayatSertifikasis;
use App\Filament\Resources\DataRiwayatSertifikasis\Pages\ViewDataRiwayatSertifikasi;
use App\Filament\Resources\DataRiwayatSertifikasis\Schemas\DataRiwayatSertifikasiForm;
use App\Filament\Resources\DataRiwayatSertifikasis\Tables\DataRiwayatSertifikasisTable;
use App\Models\DataRiwayatSertifikasi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

/**
 * DataRiwayatSertifikasiResource
 *
 * Resource untuk mengelola data riwayat sertifikasi dalam Filament Admin Panel
 */
class DataRiwayatSertifikasiResource extends Resource
{
    protected static ?string $model = DataRiwayatSertifikasi::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Data Sertifikasi';

    protected static ?string $modelLabel = 'Data Riwayat Sertifikasi';

    protected static ?string $pluralModelLabel = 'Data Riwayat Sertifikasi';

    protected static string|UnitEnum|null $navigationGroup = 'Data Pegawai';

    protected static ?int $navigationSort = 5;

    /**
     * Form configuration
     */
    public static function form(Schema $schema): Schema
    {
        return DataRiwayatSertifikasiForm::configure($schema);
    }

    /**
     * Table configuration
     */
    public static function table(Table $table): Table
    {
        return DataRiwayatSertifikasisTable::configure($table);
    }

    /**
     * Get relations
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Get pages
     */
    public static function getPages(): array
    {
        return [
            'index' => ListDataRiwayatSertifikasis::route('/'),
            'create' => CreateDataRiwayatSertifikasi::route('/create'),
            'view' => ViewDataRiwayatSertifikasi::route('/{record}'),
            'edit' => EditDataRiwayatSertifikasi::route('/{record}/edit'),
        ];
    }

    /**
     * Get global search result details
     */
    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Pegawai' => $record->pegawai?->nama_lengkap,
            'NIK' => $record->pegawai?->nik,
            'Nomor' => $record->nomor,
            'Tahun' => $record->tahun,
        ];
    }

    /**
     * Get global search result title
     */
    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->nama ?? 'Sertifikasi';
    }

    /**
     * Get global search result url
     */
    public static function getGlobalSearchResultUrl($record): string
    {
        return static::getUrl('view', ['record' => $record]);
    }
}
