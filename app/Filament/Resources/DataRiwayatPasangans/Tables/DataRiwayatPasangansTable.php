<?php

namespace App\Filament\Resources\DataRiwayatPasangans\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class DataRiwayatPasangansTable
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

                \Filament\Tables\Columns\TextColumn::make('nama_pasangan')
                    ->label('Nama Pasangan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                \Filament\Tables\Columns\TextColumn::make('hubungan')
                    ->label('Hubungan')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Suami' => 'info',
                        'Istri' => 'success',
                        default => 'gray',
                    }),

                \Filament\Tables\Columns\TextColumn::make('birth_info')
                    ->label('Tempat, Tanggal Lahir')
                    ->getStateUsing(function ($record) {
                        $parts = [];
                        if ($record->tempat_lahir) {
                            $parts[] = $record->tempat_lahir;
                        }
                        if ($record->tanggal_lahir) {
                            $parts[] = $record->tanggal_lahir->format('d/m/Y');
                        }
                        return implode(', ', $parts) ?: '-';
                    })
                    ->wrap(),

                \Filament\Tables\Columns\TextColumn::make('age')
                    ->label('Usia')
                    ->getStateUsing(function ($record) {
                        return $record->age ? $record->age . ' tahun' : '-';
                    })
                    ->alignCenter(),

                \Filament\Tables\Columns\TextColumn::make('jenjangPendidikan.nama_jenjang_pendidikan')
                    ->label('Pendidikan')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                \Filament\Tables\Columns\TextColumn::make('pekerjaan.nama_pekerjaan')
                    ->label('Pekerjaan')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

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

                \Filament\Tables\Filters\SelectFilter::make('hubungan')
                    ->label('Hubungan')
                    ->options([
                        'Suami' => 'Suami',
                        'Istri' => 'Istri',
                    ])
                    ->native(false),

                \Filament\Tables\Filters\SelectFilter::make('kode_jenjang_pendidikan')
                    ->label('Jenjang Pendidikan')
                    ->options(
                        \App\Models\JenjangPendidikan::query()
                            ->where('status', '1')
                            ->orderBy('urut')
                            ->pluck('nama_jenjang_pendidikan', 'kode')
                    )
                    ->searchable()
                    ->preload(),

                \Filament\Tables\Filters\SelectFilter::make('kode_tabel_pekerjaan')
                    ->label('Pekerjaan')
                    ->options(
                        \App\Models\TabelPekerjaan::query()
                            ->where('status', '1')
                            ->orderBy('urut')
                            ->pluck('nama_pekerjaan', 'kode')
                    )
                    ->searchable()
                    ->preload(),

                \Filament\Tables\Filters\Filter::make('tanggal_lahir')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('tanggal_dari')
                            ->label('Tanggal Lahir Dari')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        \Filament\Forms\Components\DatePicker::make('tanggal_sampai')
                            ->label('Tanggal Lahir Sampai')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['tanggal_dari'], fn($query, $tanggal) => $query->where('tanggal_lahir', '>=', $tanggal))
                            ->when($data['tanggal_sampai'], fn($query, $tanggal) => $query->where('tanggal_lahir', '<=', $tanggal));
                    }),

                \Filament\Tables\Filters\Filter::make('pasangan_usia')
                    ->form([
                        \Filament\Forms\Components\Select::make('kategori_usia')
                            ->label('Kategori Usia')
                            ->options([
                                'muda' => 'Muda (< 30 tahun)',
                                'dewasa' => 'Dewasa (30-50 tahun)',
                                'tua' => 'Tua (> 50 tahun)',
                            ])
                            ->native(false),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['kategori_usia'], function ($query, $kategori) {
                            $today = now();
                            switch ($kategori) {
                                case 'muda':
                                    $query->where('tanggal_lahir', '>', $today->copy()->subYears(30));
                                    break;
                                case 'dewasa':
                                    $query->whereBetween('tanggal_lahir', [
                                        $today->copy()->subYears(50),
                                        $today->copy()->subYears(30)
                                    ]);
                                    break;
                                case 'tua':
                                    $query->where('tanggal_lahir', '<', $today->copy()->subYears(50));
                                    break;
                            }
                        });
                    }),
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
