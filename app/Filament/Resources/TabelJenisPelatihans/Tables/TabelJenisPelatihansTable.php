<?php

namespace App\Filament\Resources\TabelJenisPelatihans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use App\Filament\Resources\TabelJenisPelatihans\TabelJenisPelatihanResource;

/**
 * TabelJenisPelatihansTable
 * 
 * Konfigurasi table untuk TabelJenisPelatihan Resource.
 * Menyediakan kolom, filter, dan aksi untuk tabel jenis pelatihan.
 * 
 * @package App\Filament\Resources\TabelJenisPelatihans\Tables
 * @author Laravel Filament
 * @version 1.0.0
 */
class TabelJenisPelatihansTable
{
    /**
     * Konfigurasi table untuk TabelJenisPelatihan
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
                TextColumn::make('nama_jenis_pelatihan')
                    ->label('Nama Jenis Pelatihan')
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
                    ->form(fn (Schema $schema) => TabelJenisPelatihanResource::form($schema))
                    ->modalHeading('Edit Jenis Pelatihan')
                    ->modalSubmitActionLabel('Save Changes')
                    ->modalCancelActionLabel('Cancel')
                    ->successNotificationTitle('Jenis Pelatihan updated successfully!')
                    ->after(fn () => redirect()->to(TabelJenisPelatihanResource::getUrl('index'))),
                DeleteAction::make()
                    ->label('Delete')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Jenis Pelatihan')
                    ->modalDescription('Are you sure you want to delete this jenis pelatihan? This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, delete it')
                    ->modalCancelActionLabel('Cancel'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Delete Selected')
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Delete Selected Jenis Pelatihans')
                        ->modalDescription('Are you sure you want to delete the selected jenis pelatihans? This action cannot be undone.')
                        ->modalSubmitActionLabel('Yes, delete them')
                        ->modalCancelActionLabel('Cancel'),
                ]),
            ]);
    }
}