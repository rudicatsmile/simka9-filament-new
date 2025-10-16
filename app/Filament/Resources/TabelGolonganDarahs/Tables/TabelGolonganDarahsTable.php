<?php

namespace App\Filament\Resources\TabelGolonganDarahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use App\Filament\Resources\TabelGolonganDarahs\TabelGolonganDarahResource;

class TabelGolonganDarahsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    // ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('nama_golongan_darah')
                    ->label('Nama Golongan Darah')
                    // ->searchable(isIndividual: true)
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
            // ->headerActions([
            //     CreateAction::make()
            //         ->label('Create Golongan Darah')
            //         ->icon('heroicon-m-plus')
            //         ->color('primary')
            //         ->form(fn(Schema $schema) => TabelGolonganDarahResource::form($schema))
            //         ->modalHeading('Create New Golongan Darah')
            //         ->modalSubmitActionLabel('Create')
            //         ->modalCancelActionLabel('Cancel')
            //         ->successNotificationTitle('Golongan Darah created successfully!')
            //         ->after(fn() => redirect()->to(TabelGolonganDarahResource::getUrl('index'))),
            // ])
            ->actions([
                EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning')
                    ->form(fn(Schema $schema) => TabelGolonganDarahResource::form($schema))
                    ->modalHeading('Edit Golongan Darah')
                    ->modalSubmitActionLabel('Save Changes')
                    ->modalCancelActionLabel('Cancel')
                    ->successNotificationTitle('Golongan Darah updated successfully!')
                    ->after(fn() => redirect()->to(TabelGolonganDarahResource::getUrl('index')))
                    ->visible(fn () => auth()->user()?->hasPermission('tabel-golongan-darahs.edit') ?? false),
                DeleteAction::make()
                    ->label('Delete')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Golongan Darah')
                    ->modalDescription('Are you sure you want to delete this golongan darah? This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, delete it')
                    ->modalCancelActionLabel('Cancel')
                    ->visible(fn () => auth()->user()?->hasPermission('tabel-golongan-darahs.delete') ?? false),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Delete Selected')
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Delete Selected Golongan Darah')
                        ->modalDescription('Are you sure you want to delete the selected golongan darah? This action cannot be undone.')
                        ->modalSubmitActionLabel('Yes, delete them')
                        ->modalCancelActionLabel('Cancel')
                        ->visible(fn () => auth()->user()?->hasPermission('tabel-golongan-darahs.delete') ?? false),
                ]),
            ]);
    }
}
