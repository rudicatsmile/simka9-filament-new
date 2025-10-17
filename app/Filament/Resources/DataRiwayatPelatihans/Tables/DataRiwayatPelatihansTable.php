<?php

namespace App\Filament\Resources\DataRiwayatPelatihans\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Facades\Storage;

class DataRiwayatPelatihansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('pegawai.nama_lengkap')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),

                // \Filament\Tables\Columns\TextColumn::make('pegawai.nip')
                //     ->label('NIP')
                //     ->searchable()
                //     ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Pelatihan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                // \Filament\Tables\Columns\TextColumn::make('jenisPelatihan.nama_jenis_pelatihan')
                //     ->label('Jenis Pelatihan')
                //     ->searchable()
                //     ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('penyelenggara')
                    ->label('Penyelenggara')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                \Filament\Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal Pelatihan')
                    ->date('d/m/Y')
                    ->sortable(),

                // \Filament\Tables\Columns\TextColumn::make('tanggal_sertifikat')
                //     ->label('Tanggal Sertifikat')
                //     ->date('d/m/Y')
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),

                // \Filament\Tables\Columns\TextColumn::make('nomor')
                //     ->label('Nomor Sertifikat')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: true),

                \Filament\Tables\Columns\IconColumn::make('berkas')
                    ->label('Sertifikat')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-check')
                    ->falseIcon('heroicon-o-document-minus')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->getStateUsing(fn($record) => !empty($record->berkas)),

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

                \Filament\Tables\Filters\SelectFilter::make('kode_tabel_jenis_pelatihan')
                    ->label('Jenis Pelatihan')
                    ->options(
                        \App\Models\TabelJenisPelatihan::query()
                            ->orderBy('nama_jenis_pelatihan')
                            ->pluck('nama_jenis_pelatihan', 'kode')
                    )
                    ->searchable()
                    ->preload(),

                \Filament\Tables\Filters\Filter::make('tanggal_pelatihan')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('tanggal_dari')
                            ->label('Tanggal Dari')
                            ->displayFormat('d/m/Y'),
                        \Filament\Forms\Components\DatePicker::make('tanggal_sampai')
                            ->label('Tanggal Sampai')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['tanggal_dari'], fn($query, $tanggal) => $query->where('tanggal', '>=', $tanggal))
                            ->when($data['tanggal_sampai'], fn($query, $tanggal) => $query->where('tanggal', '<=', $tanggal));
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->iconButton()
                    ->tooltip('Download Sertifikat')
                    ->color('success')
                    ->visible(fn($record) => !empty($record->berkas))
                    ->url(fn($record) => route('admin.data-riwayat-pelatihans.download', ['id' => $record->id]))
                    ->openUrlInNewTab(),
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
