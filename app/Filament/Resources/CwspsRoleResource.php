<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\CwspsPermission;
use Filament\Tables;
use App\Models\CwspsRole;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CwspsRoleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Schemas\Components\Fieldset;

class CwspsRoleResource extends Resource
{
    protected static ?string $model = CwspsRole::class;

    protected static ?string $navigationLabel = 'Roles';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Role & Permissions')
                    ->schema([
                        Fieldset::make('Informasi Role')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Role')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('identifier')
                                    ->label('Identifier')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('status')
                                    ->label('Status')
                                    ->default(true)
                                    ->required(),
                            ])
                        ,

                        Fieldset::make('Permissions')
                            ->schema([
                                Forms\Components\CheckboxList::make('permissions')
                                    ->label('Pilih Permissions')
                                    ->relationship('permissions', 'name')
                                    ->options(CwspsPermission::pluck('name', 'id'))
                                ,
                            ]),
                    ])
                ,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Role')
                    ->searchable(),
                Tables\Columns\TextColumn::make('identifier')
                    ->label('Identifier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('permissions_count')
                    ->label('Jumlah Permission')
                    ->counts('permissions'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Inactive')
                    ->colors([
                        'success' => fn($state) => (bool) $state === true,
                        'danger' => fn($state) => (bool) $state === false,
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCwspsRoles::route('/'),
            'create' => Pages\CreateCwspsRole::route('/create'),
            'edit' => Pages\EditCwspsRole::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('roles.view') ?? false;
    }

    public static function canViewAny(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('roles.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('roles.edit') ?? false;
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        return $user?->hasPermission('roles.delete') ?? false;
    }
}
