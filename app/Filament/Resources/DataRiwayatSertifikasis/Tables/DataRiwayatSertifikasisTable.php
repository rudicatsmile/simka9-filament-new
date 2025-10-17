<?php

namespace App\Filament\Resources\DataRiwayatSertifikasis\Tables;

use Filament\Tables\Table;
use App\Models\DataPegawai;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\DataRiwayatSertifikasi;

/**
 * DataRiwayatSertifikasisTable
 *
 * Table configuration untuk DataRiwayatSertifikasi Resource
 */
class DataRiwayatSertifikasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pegawai.nama_lengkap')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),

                // TextColumn::make('pegawai.nik')
                //     ->label('NIK')
                //     ->searchable()
                //     ->sortable(),


                TextColumn::make('nama')
                    ->label('Nama Sertifikasi')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('nomor')
                    ->label('Nomor Sertifikat')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tahun')
                    ->label('Tahun')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('induk_inpasing')
                    ->label('Induk Inpasing')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sk_inpasing')
                    ->label('SK Inpasing')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('tahun_inpasing')
                    ->label('Tahun Inpasing')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),

                // TextColumn::make('urut')
                //     ->label('Urutan')
                //     ->sortable()
                //     ->alignCenter(),

                // IconColumn::make('berkas')
                //     ->label('Berkas')
                //     ->boolean()
                //     ->trueIcon('heroicon-o-document-text')
                //     ->falseIcon('heroicon-o-x-mark')
                //     ->trueColor('success')
                //     ->falseColor('gray')
                //     ->getStateUsing(fn($record) => !empty($record->berkas))
                //     ->alignCenter(),

                IconColumn::make('is_sertifikasi')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
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
                \Filament\Tables\Filters\Filter::make('unit_kerja_pegawai')
                    ->form([
                        \Filament\Forms\Components\Select::make('unit_kerja')
                            ->label('Unit Kerja')
                            ->options(
                                \App\Models\UnitKerja::query()
                                    ->orderBy('urut')
                                    ->pluck('nama_unit_kerja', 'kode')
                            )
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (callable $set) {
                                $set('data_pegawai', null);
                            }),

                        \Filament\Forms\Components\Select::make('data_pegawai')
                            ->label('Data Pegawai')
                            ->options(function (callable $get) {
                                $unitKerjaKode = $get('unit_kerja');

                                $query = \App\Models\DataPegawai::query()
                                    ->select('nik', 'nip', 'nama_lengkap');

                                if ($unitKerjaKode) {
                                    $query->where('kode_unit_kerja', $unitKerjaKode);
                                }

                                return $query->orderBy('nama_lengkap')
                                    ->get()
                                    ->mapWithKeys(function ($pegawai) {
                                        return [$pegawai->nik => $pegawai->nama_lengkap . ' (' . $pegawai->nip . ')'];
                                    })
                                    ->toArray();
                            })
                            ->searchable()
                            ->preload(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['unit_kerja'] ?? null, function ($query, $kodeUnitKerja) {
                                $query->whereHas('pegawai', function ($query) use ($kodeUnitKerja) {
                                    $query->where('kode_unit_kerja', $kodeUnitKerja);
                                });
                            })
                            ->when($data['data_pegawai'] ?? null, function ($query, $nikPegawai) {
                                $query->where('nik_data_pegawai', $nikPegawai);
                            });
                    }),
                SelectFilter::make('is_sertifikasi')
                    ->label('Status Sertifikasi')
                    ->options([
                        1 => 'Sertifikasi',
                        0 => 'Non-Sertifikasi',
                    ]),

                SelectFilter::make('tahun')
                    ->label('Tahun')
                    ->options(
                        DataRiwayatSertifikasi::query()
                            ->distinct()
                            ->orderBy('tahun', 'desc')
                            ->pluck('tahun', 'tahun')
                            ->toArray()
                    ),

                Filter::make('has_berkas')
                    ->label('Memiliki Berkas')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('berkas')),

                Filter::make('no_berkas')
                    ->label('Tanpa Berkas')
                    ->query(fn(Builder $query): Builder => $query->whereNull('berkas')),

                Filter::make('has_inpasing')
                    ->label('Memiliki Inpasing')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('induk_inpasing')),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (DataRiwayatSertifikasi $record) {
                        // Delete file when record is deleted
                        if ($record->berkas && Storage::exists($record->berkas)) {
                            Storage::delete($record->berkas);
                        }
                    }),
                Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn(DataRiwayatSertifikasi $record): string => $record->berkas_url ?? '#')
                    ->openUrlInNewTab()
                    ->visible(fn(DataRiwayatSertifikasi $record): bool => !empty($record->berkas)),
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
}
