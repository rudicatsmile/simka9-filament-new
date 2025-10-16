<?php

namespace App\Filament\Resources\TabelStatusNikahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use App\Filament\Resources\TabelStatusNikahs\TabelStatusNikahResource;

/**
 * TabelStatusNikahsTable
 * 
 * Konfigurasi table untuk TabelStatusNikah Resource.
 * Menyediakan kolom, filter, dan aksi untuk tabel status nikah.
 * 
 * @package App\Filament\Resources\TabelStatusNikahs\Tables
 * @author Laravel Filament
 * @version 1.0.0
 */
class TabelStatusNikahsTable
{
    /**
     * Konfigurasi table untuk TabelStatusNikah
     * 
     * @param Table $table
     * @return Table
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('nama_status_nikah')
                    ->label('Nama Status Nikah')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => $state === '1' ? 'Active' : 'Inactive')
                    ->colors([
                        'success' => '1',
                        'danger' => '0',
                    ]),
                TextColumn::make('urut')
                    ->label('Urutan')
                    ->sortable()
                    ->numeric(),
            ])
            ->defaultSort('urut', 'asc')
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Create Status Nikah')
                    ->icon('heroicon-m-plus')
                    ->color('primary')
                    ->form(fn (Schema $schema) => TabelStatusNikahResource::form($schema))
                    ->modalHeading('Create New Status Nikah')
                    ->modalSubmitActionLabel('Create')
                    ->modalCancelActionLabel('Cancel')
                    ->successNotificationTitle('Status Nikah created successfully!')
                    ->after(fn () => redirect()->to(TabelStatusNikahResource::getUrl('index'))),
            ])
            ->actions([
                EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning')
                    ->form(fn (Schema $schema) => TabelStatusNikahResource::form($schema))
                    ->modalHeading('Edit Status Nikah')
                    ->modalSubmitActionLabel('Save Changes')
                    ->modalCancelActionLabel('Cancel')
                    ->successNotificationTitle('Status Nikah updated successfully!')
                    ->after(fn () => redirect()->to(TabelStatusNikahResource::getUrl('index')))
                    ->visible(fn () => auth()->user()?->hasPermission('tabel-status-nikahs.edit') ?? false),
                DeleteAction::make()
                    ->label('Delete')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Status Nikah')
                    ->modalDescription('Are you sure you want to delete this status nikah? This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, delete it')
                    ->modalCancelActionLabel('Cancel')
                    ->visible(fn () => auth()->user()?->hasPermission('tabel-status-nikahs.delete') ?? false),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Delete Selected')
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Delete Selected Status Nikah')
                        ->modalDescription('Are you sure you want to delete the selected status nikah? This action cannot be undone.')
                        ->modalSubmitActionLabel('Yes, delete them')
                        ->modalCancelActionLabel('Cancel')
                        ->visible(fn () => auth()->user()?->hasPermission('tabel-status-nikahs.delete') ?? false),
                ]),
            ]);
    }
}
