<?php

namespace App\Filament\Resources\TabelHubunganKeluargas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use App\Filament\Resources\TabelHubunganKeluargas\TabelHubunganKeluargaResource;

class TabelHubunganKeluargasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    // ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('nama_hubungan_keluarga')
                    ->label('Nama Hubungan Keluarga')
                    // ->searchable(isIndividual: true)
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn(string $state): string => $state === '1' ? 'Active' : 'Inactive')
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
                    ->form(fn(Schema $schema) => TabelHubunganKeluargaResource::form($schema))
                    ->modalHeading('Edit Hubungan Keluarga')
                    ->modalSubmitActionLabel('Save Changes')
                    ->modalCancelActionLabel('Cancel')
                    ->successNotificationTitle('Hubungan keluarga updated successfully!')
                    ->after(fn() => redirect()->to(TabelHubunganKeluargaResource::getUrl('index'))),
                DeleteAction::make()
                    ->label('Delete')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Hubungan Keluarga')
                    ->modalDescription('Are you sure you want to delete this hubungan keluarga? This action cannot be undone.')
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
                        ->modalHeading('Delete Selected Hubungan Keluarga')
                        ->modalDescription('Are you sure you want to delete the selected hubungan keluarga? This action cannot be undone.')
                        ->modalSubmitActionLabel('Yes, delete them')
                        ->modalCancelActionLabel('Cancel'),
                ]),
            ]);
    }
}
