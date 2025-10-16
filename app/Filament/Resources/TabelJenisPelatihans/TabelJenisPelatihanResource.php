<?php

namespace App\Filament\Resources\TabelJenisPelatihans;

use App\Filament\Resources\TabelJenisPelatihans\Pages\ListTabelJenisPelatihans;
use App\Filament\Resources\TabelJenisPelatihans\Schemas\TabelJenisPelatihanForm;
use App\Filament\Resources\TabelJenisPelatihans\Tables\TabelJenisPelatihansTable;
use App\Models\TabelJenisPelatihan;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * TabelJenisPelatihanResource
 * 
 * Filament Resource untuk mengelola data jenis pelatihan.
 * Menyediakan interface CRUD untuk tabel_jenis_pelatihan.
 * 
 * @package App\Filament\Resources\TabelJenisPelatihans
 * @author Laravel Filament
 * @version 1.0.0
 */
class TabelJenisPelatihanResource extends Resource
{
    /**
     * Model yang digunakan oleh resource ini
     * 
     * @var string
     */
    protected static ?string $model = TabelJenisPelatihan::class;

    /**
     * Label navigasi untuk resource ini
     * 
     * @var string
     */
    protected static ?string $navigationLabel = 'Jenis Pelatihan';
    
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
        return TabelJenisPelatihanForm::configure($schema);
    }

    /**
     * Konfigurasi table untuk resource ini
     * 
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return TabelJenisPelatihansTable::configure($table);
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
            'index' => ListTabelJenisPelatihans::route('/'),
        ];
    }
}