<?php

namespace App\Filament\Resources\TabelPekerjaans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use App\Filament\Resources\TabelPekerjaans\TabelPekerjaanResource;

/**
 * TabelPekerjaansTable
 * 
 * Konfigurasi table untuk TabelPekerjaan Resource.
 * Menyediakan kolom, filter, dan aksi untuk tabel pekerjaan.
 * 
 * @package App\Filament\Resources\TabelPekerjaans\Tables
 * @author Laravel Filament
 * @version 1.0.0
 */
class TabelPekerjaansTable
{
    /**
     * Konfigurasi table untuk TabelPekerjaan
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
                TextColumn::make('nama_pekerjaan')
                    ->label('Nama Pekerjaan')
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
            ->actions([
                EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning')
                    ->form(fn (Schema $schema) => TabelPekerjaanResource::form($schema))
                    ->modalHeading('Edit Pekerjaan')
                    ->modalSubmitActionLabel('Save Changes')
                    ->modalCancelActionLabel('Cancel')
                    ->successNotificationTitle('Pekerjaan updated successfully!')
                    ->after(fn () => redirect()->to(TabelPekerjaanResource::getUrl('index')))
                    ->visible(fn () => auth()->user()?->hasPermission('tabel-pekerjaan.edit') ?? false),
                DeleteAction::make()
                    ->label('Delete')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Pekerjaan')
                    ->modalDescription('Are you sure you want to delete this pekerjaan? This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, delete it')
                    ->modalCancelActionLabel('Cancel')
                    ->visible(fn () => auth()->user()?->hasPermission('tabel-pekerjaan.delete') ?? false),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Delete Selected')
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Delete Selected Pekerjaans')
                        ->modalDescription('Are you sure you want to delete the selected pekerjaans? This action cannot be undone.')
                        ->modalSubmitActionLabel('Yes, delete them')
                        ->modalCancelActionLabel('Cancel')
                        ->visible(fn () => auth()->user()?->hasPermission('tabel-pekerjaan.delete') ?? false),
                ]),
            ]);
    }
}