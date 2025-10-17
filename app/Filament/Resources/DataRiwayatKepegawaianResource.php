<?php

namespace App\Filament\Resources;

use UnitEnum;
use BackedEnum;
use Filament\Tables\Table;
use App\Models\DataPegawai;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use App\Models\DataRiwayatKepegawaian;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DataRiwayatKepegawaianResource\Pages;
use App\Filament\Resources\DataRiwayatKepegawaianResource\Schemas\DataRiwayatKepegawaianForm;

/**
 * DataRiwayatKepegawaianResource
 *
 * Resource untuk mengelola data riwayat kepegawaian dalam Filament Admin Panel
 */
class DataRiwayatKepegawaianResource extends Resource
{
    protected static ?string $model = DataRiwayatKepegawaian::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Data Kepegawaian';

    protected static ?string $modelLabel = 'Data Riwayat Kepegawaian';

    protected static ?string $pluralModelLabel = 'Data Riwayat Kepegawaian';

    protected static string|UnitEnum|null $navigationGroup = 'Data Pegawai';

    protected static ?int $navigationSort = 4;

    /**
     * Form configuration
     */
    public static function form(Schema $schema): Schema
    {
        return DataRiwayatKepegawaianForm::configure($schema);
    }

    /**
     * Table configuration
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pegawai.nama_lengkap')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pegawai.nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama')
                    ->label('Nama Riwayat')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('nomor')
                    ->label('Nomor SK')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('urut')
                    ->label('Urutan')
                    ->sortable()
                    ->alignCenter(),

                IconColumn::make('berkas')
                    ->label('Berkas')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-text')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->getStateUsing(fn($record) => !empty($record->berkas))
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('nik_data_pegawai')
                    ->label('Pegawai')
                    ->options(
                        DataPegawai::query()
                            ->orderBy('nama_lengkap')
                            ->pluck('nama_lengkap', 'nik')
                            ->toArray()
                    )
                    ->searchable()
                    ->preload(),

                Filter::make('has_berkas')
                    ->label('Memiliki Berkas')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('berkas')),

                Filter::make('no_berkas')
                    ->label('Tanpa Berkas')
                    ->query(fn(Builder $query): Builder => $query->whereNull('berkas')),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (DataRiwayatKepegawaian $record) {
                        // Delete file when record is deleted
                        if ($record->berkas && Storage::exists($record->berkas)) {
                            Storage::delete($record->berkas);
                        }
                    }),
                Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn(DataRiwayatKepegawaian $record): string => $record->berkas_url ?? '#')
                    ->openUrlInNewTab()
                    ->visible(fn(DataRiwayatKepegawaian $record): bool => !empty($record->berkas)),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function ($records) {
                            // Delete files when records are bulk deleted
                            foreach ($records as $record) {
                                if ($record->berkas && Storage::exists($record->berkas)) {
                                    Storage::delete($record->berkas);
                                }
                            }
                        }),
                ]),
            ])
            ->defaultSort('urut', 'asc');
    }

    /**
     * Get relations
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Get pages
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDataRiwayatKepegawaians::route('/'),
            'create' => Pages\CreateDataRiwayatKepegawaian::route('/create'),
            'view' => Pages\ViewDataRiwayatKepegawaian::route('/{record}'),
            'edit' => Pages\EditDataRiwayatKepegawaian::route('/{record}/edit'),
        ];
    }

    /**
     * Get global search result details
     */
    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Pegawai' => $record->pegawai?->nama,
            'NIK' => $record->pegawai?->nik,
            'Nomor' => $record->nomor,
        ];
    }

    /**
     * Get global search result title
     */
    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->nama ?? 'Riwayat Kepegawaian';
    }

    /**
     * Get navigation badge
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    /**
     * Get navigation badge color
     */
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }
}
