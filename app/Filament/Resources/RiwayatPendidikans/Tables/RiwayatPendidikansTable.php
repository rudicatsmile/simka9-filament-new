<?php

namespace App\Filament\Resources\RiwayatPendidikans\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class RiwayatPendidikansTable
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

                \Filament\Tables\Columns\TextColumn::make('jenjangPendidikan.nama_jenjang_pendidikan')
                    ->label('Jenjang Pendidikan')
                    ->searchable()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('nama_sekolah')
                    ->label('Nama Sekolah')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                \Filament\Tables\Columns\TextColumn::make('tahun_ijazah')
                    ->label('Tahun Ijazah')
                    ->sortable()
                    ->alignCenter(),

                // \Filament\Tables\Columns\TextColumn::make('urut')
                //     ->label('Urutan')
                //     ->sortable()
                //     ->alignCenter(),

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

                \Filament\Tables\Filters\SelectFilter::make('kode_jenjang_pendidikan')
                    ->label('Jenjang Pendidikan')
                    ->options(
                        \App\Models\JenjangPendidikan::query()
                            ->where('status', '1')
                            ->orderBy('urut')
                            ->pluck('nama_jenjang_pendidikan', 'kode')
                    )
                    ->searchable()
                    ->preload()

                // \Filament\Tables\Filters\Filter::make('tahun_ijazah')
                //     ->form([
                //         \Filament\Forms\Components\TextInput::make('tahun_dari')
                //             ->label('Tahun Dari')
                //             ->numeric()
                //             ->placeholder('Contoh: 2000'),
                //         \Filament\Forms\Components\TextInput::make('tahun_sampai')
                //             ->label('Tahun Sampai')
                //             ->numeric()
                //             ->placeholder('Contoh: 2023'),
                // ])
                // ->query(function ($query, array $data) {
                //     return $query
                //         ->when($data['tahun_dari'], fn($query, $tahun) => $query->where('tahun_ijazah', '>=', $tahun))
                //         ->when($data['tahun_sampai'], fn($query, $tahun) => $query->where('tahun_ijazah', '<=', $tahun));
                // }),
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
