<?php

namespace App\Filament\Resources\DataPegawais;

use App\Filament\Resources\DataPegawais\Pages\CreateDataPegawai;
use App\Filament\Resources\DataPegawais\Pages\EditDataPegawai;
use App\Filament\Resources\DataPegawais\Pages\ListDataPegawais;
use App\Filament\Resources\DataPegawais\Schemas\DataPegawaiForm;
use App\Filament\Resources\DataPegawais\Tables\DataPegawaisTable;
use App\Models\DataPegawai;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * DataPegawaiResource
 * 
 * Filament Resource untuk mengelola data pegawai.
 * Menyediakan interface CRUD untuk data_pegawai.
 * 
 * @package App\Filament\Resources\DataPegawais
 * @author Laravel Filament
 * @version 1.0.0
 */
class DataPegawaiResource extends Resource
{
    /**
     * Model yang digunakan oleh resource ini
     * 
     * @var string
     */
    protected static ?string $model = DataPegawai::class;

    /**
     * Icon untuk navigasi
     * 
     * @var string|BackedEnum|null
     */
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    /**
     * Grup navigasi
     * 
     * @var string|UnitEnum|null
     */
    protected static string | UnitEnum | null $navigationGroup = 'Riwayat Pegawai';

    /**
     * Label navigasi
     * 
     * @var string|null
     */
    protected static ?string $navigationLabel = 'Data Pegawai';

    /**
     * Label model
     * 
     * @var string|null
     */
    protected static ?string $modelLabel = 'Data Pegawai';

    /**
     * Label model plural
     * 
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Data Pegawai';

    /**
     * Urutan navigasi
     * 
     * @var int|null
     */
    protected static ?int $navigationSort = 1;

    /**
     * Atribut untuk title record
     * 
     * @var string|null
     */
    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public static function form(Schema $schema): Schema
    {
        return DataPegawaiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DataPegawaisTable::configure($table);
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
            'index' => ListDataPegawais::route('/'),
            'create' => CreateDataPegawai::route('/create'),
            'edit' => EditDataPegawai::route('/{record}/edit'),
        ];
    }
}
