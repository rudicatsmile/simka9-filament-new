<?php

namespace App\Filament\Resources\JabatanUtamas\Tables;

use App\Models\UnitKerja;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\JabatanUtamas\JabatanUtamaResource;

/**
 * Table configuration untuk JabatanUtama
 *
 * Class ini mengkonfigurasi tampilan table untuk model JabatanUtama
 * termasuk kolom, filter, actions, dan bulk actions
 */
class JabatanUtamasTable
{
    /**
     * Konfigurasi table untuk JabatanUtama
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
                TextColumn::make('unitKerja.nama_unit_kerja')
                    ->label('Unit Kerja')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('nama_jabatan_utama')
                    ->label('Nama Jabatan Utama')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn(string $state): string => $state === '1' ? 'Active' : 'Inactive')
                    ->colors([
                        'success' => '1',
                        'danger' => '0',
                    ])
                    ->sortable(),
                TextColumn::make('urut')
                    ->label('Urut')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('urut', 'asc')
            ->filters([

                SelectFilter::make('kode_unit_kerja')
                    ->label('Unit Kerja')
                    ->options(
                        UnitKerja::query()
                            ->where('status', '1')
                            ->orderBy('urut')
                            ->pluck('nama_unit_kerja', 'kode')
                            ->toArray()
                    )
                    ->searchable()
                    ->preload()
                    ->native(false),

                SelectFilter::make('status')
                    ->label('Status Aktif')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ])
                    ->native(false),
            ])
            ->actions([
                EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning')
                    ->modalHeading('Edit Jabatan Utama')
                    ->modalSubmitActionLabel('Update')
                    ->modalCancelActionLabel('Cancel')
                    ->successNotificationTitle('Jabatan Utama updated successfully!')
                    ->after(fn() => redirect()->to(JabatanUtamaResource::getUrl('index'))),
                DeleteAction::make()
                    ->visible(fn() => auth()->user()?->hasPermission('jabatan-utamas.edit') ?? false),
                DeleteAction::make()
                    ->label('Delete')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Jabatan Utama')
                    ->modalDescription('Are you sure you want to delete this jabatan utama? This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, delete it')
                    ->modalCancelActionLabel('Cancel')
                    ->visible(fn() => auth()->user()?->hasPermission('jabatan-utamas.delete') ?? false),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Delete Selected')
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Delete Selected Jabatan Utama')
                        ->modalDescription('Are you sure you want to delete the selected jabatan utama? This action cannot be undone.')
                        ->modalSubmitActionLabel('Yes, delete them')
                        ->modalCancelActionLabel('Cancel')
                        ->visible(fn() => auth()->user()?->hasPermission('jabatan-utamas.delete') ?? false),
                ]),
            ]);
    }
}
