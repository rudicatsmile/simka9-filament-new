<?php

namespace App\Filament\Resources\DataRiwayatUnitKerjaLains\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class DataRiwayatUnitKerjaLainsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('pegawai.nama_lengkap')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('pegawai.nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable(),

                \Filament\Tables\Columns\IconColumn::make('is_bekerja_di_tempat_lain')
                    ->label('Bekerja di Tempat Lain')
                    ->boolean()
                    ->alignCenter(),

                \Filament\Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'aktif' => 'success',
                        'tidak_aktif' => 'warning',
                        'selesai' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'aktif' => 'Aktif',
                        'tidak_aktif' => 'Tidak Aktif',
                        'selesai' => 'Selesai',
                        default => $state,
                    }),

                // \Filament\Tables\Columns\TextColumn::make('nama')
                //     ->label('Nama Instansi')
                //     ->searchable()
                //     ->sortable()
                //     ->wrap(),

                // \Filament\Tables\Columns\TextColumn::make('jabatan')
                //     ->label('Jabatan')
                //     ->searchable()
                //     ->sortable()
                //     ->wrap(),

                // \Filament\Tables\Columns\TextColumn::make('fungsi')
                //     ->label('Fungsi/Bidang')
                //     ->searchable()
                //     ->sortable()
                //     ->wrap(),

                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                \Filament\Tables\Columns\TextColumn::make('updated_at')
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
                            ->when($data['unit_kerja'], function ($query, $kodeUnitKerja) {
                                $query->whereHas('pegawai', function ($query) use ($kodeUnitKerja) {
                                    $query->where('kode_unit_kerja', $kodeUnitKerja);
                                });
                            })
                            ->when($data['data_pegawai'], function ($query, $nikPegawai) {
                                $query->where('nik_data_pegawai', $nikPegawai);
                            });
                    }),

                \Filament\Tables\Filters\SelectFilter::make('is_bekerja_di_tempat_lain')
                    ->label('Bekerja di Tempat Lain')
                    ->options([
                        '1' => 'Ya',
                        '0' => 'Tidak',
                    ])
                    ->native(false),

                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak_aktif' => 'Tidak Aktif',
                        'selesai' => 'Selesai',
                    ])
                    ->native(false),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
