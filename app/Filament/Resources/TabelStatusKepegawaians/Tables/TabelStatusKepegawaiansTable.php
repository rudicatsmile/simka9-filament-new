<?php

namespace App\Filament\Resources\TabelStatusKepegawaians\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TabelStatusKepegawaiansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_status_kepegawaian')
                    ->label('Nama Status Kepegawaian')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                        default => 'Unknown',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('urut')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ])
                    ->native(false),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn () => auth()->user()?->hasPermission('tabel-status-kepegawaians.edit') ?? false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->hasPermission('tabel-status-kepegawaians.delete') ?? false),
                ]),
            ])
            ->defaultSort('urut', 'asc');
    }
}
