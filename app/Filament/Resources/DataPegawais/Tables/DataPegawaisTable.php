<?php

namespace App\Filament\Resources\DataPegawais\Tables;

use App\Models\Agama;
use App\Models\UnitKerja;
use Filament\Tables\Table;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Collection;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use App\Models\TabelStatusKepegawaian;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Builder;
// use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * DataPegawaisTable
 *
 * Konfigurasi table untuk DataPegawai Resource.
 * Menyediakan kolom, filter, dan aksi untuk tabel data pegawai.
 *
 * @package App\Filament\Resources\DataPegawais\Tables
 * @author Laravel Filament
 * @version 1.0.0
 */
class DataPegawaisTable
{
    /**
     * Konfigurasi table untuk DataPegawai
     *
     * @param Table $table
     * @return Table
     */
    // public static function table(Table $table): Table
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->size(40),

                TextColumn::make('nip')
                    ->label('NIY')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('NIP berhasil disalin'),

                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('unitKerja.nama_unit_kerja')
                    ->label('Unit Kerja')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->toggleable(),

                // BadgeColumn::make('jns_kelamin')
                //     ->label('Jenis Kelamin')
                //     ->formatStateUsing(fn(string $state): string => match ($state) {
                //         '1' => 'Laki-laki',
                //         '0' => 'Perempuan',
                //         default => 'Unknown',
                //     })
                //     ->color(fn(string $state): string => match ($state) {
                //         '1' => 'info',
                //         '0' => 'warning',
                //         default => 'gray',
                //     })
                //     ->sortable(),

                // TextColumn::make('agama.nama_agama')
                //     ->label('Agama')
                //     ->searchable()
                //     ->sortable()
                //     ->toggleable(),

                // TextColumn::make('statusKepegawaian.nama_status_kepegawaian')
                //     ->label('Status Kepegawaian')
                //     ->searchable()
                //     ->sortable()
                //     ->wrap()
                //     ->toggleable(),

                BadgeColumn::make('pstatus')
                    ->label('Status')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                        default => 'Unknown',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                // BadgeColumn::make('blokir')
                //     ->label('Status Blokir')
                //     ->formatStateUsing(fn(string $state): string => $state)
                //     ->color(fn(string $state): string => match ($state) {
                //         'Tidak' => 'success',
                //         'Ya' => 'danger',
                //         default => 'gray',
                //     })
                //     ->sortable(),

                // TextColumn::make('email')
                //     ->label('Email')
                //     ->searchable()
                //     ->sortable()
                //     ->copyable()
                //     ->copyMessage('Email berhasil disalin')
                //     ->toggleable(),

                // TextColumn::make('no_tlp')
                //     ->label('No. Telepon')
                //     ->searchable()
                //     ->sortable()
                //     ->copyable()
                //     ->copyMessage('Nomor telepon berhasil disalin')
                //     ->toggleable(),

                TextColumn::make('jabatanUtama.nama_jabatan_utama')
                    ->label('Jabatan Utama')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->toggleable(),

                // TextColumn::make('jenjangPendidikan.nama_jenjang_pendidikan')
                //     ->label('Jenjang Pendidikan')
                //     ->searchable()
                //     ->sortable()
                //     ->wrap()
                //     ->toggleable(),

                // TextColumn::make('mulai_bekerja')
                //     ->label('Mulai Bekerja')
                //     ->searchable()
                //     ->sortable()
                //     ->toggleable(),

                // TextColumn::make('createdon')
                //     ->label('Dibuat')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),

                // TextColumn::make('updatedon')
                //     ->label('Diperbarui')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

                // Dropdown filter Unit Kerja (memuat data dari tabel unit_kerja)
                SelectFilter::make('kode_unit_kerja')
                    ->label('Unit Kerja')
                    ->options(
                        UnitKerja::query()
                            ->where('status', '1')
                            ->orderBy('urut')
                            ->pluck('nama_unit_kerja', 'kode')
                            ->toArray()
                    )
                    ->searchable()
                    ->preload()
                    ->native(false),


                SelectFilter::make('pstatus')
                    ->label('Status Aktif')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ])
                    ->native(false),

                SelectFilter::make('jns_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        '1' => 'Laki-laki',
                        '0' => 'Perempuan',
                    ])
                    ->native(false),

                SelectFilter::make('blokir')
                    ->label('Status Blokir')
                    ->options([
                        'Tidak' => 'Tidak',
                        'Ya' => 'Ya',
                    ])
                    ->native(false),


                SelectFilter::make('kode_agama')
                    ->label('Agama')
                    ->options(
                        Agama::query()
                            ->orderBy('urut', 'asc')
                            ->pluck('nama_agama', 'kode')
                            ->toArray()
                    )
                    ->searchable()
                    ->preload()
                    ->native(false),

                SelectFilter::make('kode_status_kepegawaian')
                    ->label('Status Kepegawaian')
                    // ->relationship('statusKepegawaian', 'nama_status_kepegawaian')
                    ->options(
                        TabelStatusKepegawaian::query()
                            ->orderBy('urut', 'asc')
                            ->pluck('nama_status_kepegawaian', 'kode')
                            ->toArray()
                    )
                    ->searchable()
                    ->preload()
                    ->native(false),
            ])
            ->filtersLayout(FiltersLayout::Dropdown)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('aktivasi')
                        ->label('Aktivasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->form([
                            Select::make('pstatus')
                                ->label('Status')
                                ->options([
                                    1 => 'Aktif',
                                    0 => 'Non Aktif',
                                ])
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $status = (int) $data['pstatus'];

                            foreach ($records as $record) {
                                $record->update(['pstatus' => $status]);
                            }

                            Notification::make()
                                ->title('Status berhasil diperbarui.')
                                ->body('Pembaruan status ke "' . ($status === 1 ? 'Aktif' : 'Non Aktif') . '" telah diterapkan pada data terpilih.')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
