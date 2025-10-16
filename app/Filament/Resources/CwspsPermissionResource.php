<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\CwspsPermission;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CwspsPermissionResource\Pages;
use App\Filament\Resources\CwspsPermissionResource\Pages\EditCwspsPermission;
use App\Filament\Resources\CwspsPermissionResource\Pages\ListCwspsPermissions;
use App\Filament\Resources\CwspsPermissionResource\Pages\CreateCwspsPermission;

class CwspsPermissionResource extends Resource
{
    protected static ?string $model = CwspsPermission::class;

    protected static ?string $navigationLabel = 'Permissions';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Permission')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Permission')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('identifier')
                            ->label('Identifier')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('route')
                            ->label('Route')
                            ->maxLength(255),
                        Forms\Components\TagsInput::make('panel_ids')
                            ->label('Panel IDs')
                            ->separator(','),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Permission')
                    ->searchable(),
                Tables\Columns\TextColumn::make('identifier')
                    ->label('Identifier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('route')
                    ->label('Route')
                    ->searchable(),
                Tables\Columns\TextColumn::make('panel_ids')
                    ->label('Panel IDs')
                    ->badge()
                    ->separator(','),
                Tables\Columns\TextColumn::make('roles_count')
                    ->label('Jumlah Role')
                    ->counts('roles'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
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
                        'active' => 'Active',
                        'inactive' => 'Inactive',
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
            'index' => Pages\ListCwspsPermissions::route('/'),
            'create' => Pages\CreateCwspsPermission::route('/create'),
            'edit' => Pages\EditCwspsPermission::route('/{record}/edit'),
        ];
    }
}
