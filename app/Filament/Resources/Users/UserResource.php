<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * Filament Resource untuk User
 *
 * Menyediakan interface CRUD untuk model User pada admin panel.
 */
class UserResource extends Resource
{
    /**
     * Model yang digunakan oleh resource.
     *
     * @var string|null
     */
    protected static ?string $model = User::class;

    /**
     * Label navigasi untuk resource.
     *
     * @var string|null
     */
    protected static ?string $navigationLabel = 'Users';

    /**
     * Grup navigasi untuk resource.
     *
     * @var string|UnitEnum|null
     */
    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    /**
     * Icon navigasi untuk resource.
     *
     * @var string|BackedEnum|null
     */
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Form konfigurasi untuk create/update via modal.
     *
     * @param Schema $schema
     * @return Schema
     */
    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    /**
     * Konfigurasi tabel list.
     *
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    /**
     * Relasi terkait resource ini.
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
     * Halaman yang tersedia untuk resource ini.
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            // 'create' => CreateUser::route('/create'), // Mengikuti pola Agama: create via modal
            // 'edit' => EditUser::route('/{record}/edit'), // Mengikuti pola Agama: edit via modal
        ];
    }
}