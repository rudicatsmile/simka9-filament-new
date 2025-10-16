<?php

namespace App\Filament\Resources\TabelPropinsis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use App\Filament\Resources\TabelPropinsis\TabelPropinsiResource;

class TabelPropinsisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('nama_propinsi')
                    ->label('Nama Propinsi')
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
                    ->form(fn (Schema $schema) => TabelPropinsiResource::form($schema))
                    ->modalHeading('Edit Propinsi')
                    ->modalSubmitActionLabel('Save Changes')
                    ->modalCancelActionLabel('Cancel')
                    ->successNotificationTitle('Propinsi updated successfully!')
                    ->after(fn () => redirect()->to(TabelPropinsiResource::getUrl('index'))),
                DeleteAction::make()
                    ->label('Delete')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Propinsi')
                    ->modalDescription('Are you sure you want to delete this propinsi? This action cannot be undone.')
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
                        ->modalHeading('Delete Selected Propinsi')
                        ->modalDescription('Are you sure you want to delete the selected propinsi? This action cannot be undone.')
                        ->modalSubmitActionLabel('Yes, delete them')
                        ->modalCancelActionLabel('Cancel'),
                ]),
            ]);
    }
}
