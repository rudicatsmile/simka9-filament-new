<?php

namespace App\Filament\Widgets;

use App\Models\DataPegawai;
use App\Models\JabatanUtama;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

/**
 * Widget untuk menampilkan statistik pegawai berdasarkan jabatan utama
 * 
 * Menampilkan jumlah pegawai pria dan wanita untuk setiap jabatan utama
 * dalam format tabel yang profesional dan responsif
 */
class EmployeeByPositionWidget extends BaseWidget
{
    /**
     * Judul widget
     *
     * @var string|null
     */
    protected static ?string $heading = 'Statistik Pegawai Berdasarkan Jabatan Utama';

    /**
     * Urutan widget di dashboard
     *
     * @var int
     */
    protected static ?int $sort = 2;

    /**
     * Tinggi widget
     *
     * @var int
     */
    protected int | string | array $columnSpan = 'full';

    /**
     * Konfigurasi tabel
     *
     * @param Table $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('nama_jabatan_utama')
                    ->label('Jabatan Utama')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->wrap(),

                TextColumn::make('male_count')
                    ->label('Pegawai Pria')
                    ->alignCenter()
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0)),

                TextColumn::make('female_count')
                    ->label('Pegawai Wanita')
                    ->alignCenter()
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0)),

                TextColumn::make('total_count')
                    ->label('Total Pegawai')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->weight('bold')
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0)),

                TextColumn::make('percentage')
                    ->label('Persentase')
                    ->alignCenter()
                    ->formatStateUsing(function ($state, $record) {
                        $total = $this->getTotalEmployees();
                        if ($total > 0) {
                            $percentage = ($record->total_count / $total) * 100;
                            return number_format($percentage, 1) . '%';
                        }
                        return '0%';
                    })
                    ->color('gray'),
            ])
            ->defaultSort('total_count', 'desc')
            ->paginated(false)
            ->striped()
            ->emptyStateHeading('Tidak ada data pegawai')
            ->emptyStateDescription('Belum ada data pegawai yang terdaftar dalam sistem.')
            ->emptyStateIcon('heroicon-o-users');
    }

    /**
     * Query untuk mendapatkan data statistik pegawai berdasarkan jabatan utama
     *
     * @return Builder
     */
    protected function getTableQuery(): Builder
    {
        return JabatanUtama::query()
            ->select([
                'jabatan_utama.kode',
                'jabatan_utama.nama_jabatan_utama',
                \DB::raw('COUNT(CASE WHEN data_pegawai.jns_kelamin = "1" THEN 1 END) as male_count'),
                \DB::raw('COUNT(CASE WHEN data_pegawai.jns_kelamin = "0" THEN 1 END) as female_count'),
                \DB::raw('COUNT(data_pegawai.id) as total_count')
            ])
            ->leftJoin('data_pegawai', 'jabatan_utama.kode', '=', 'data_pegawai.kode_jabatan_utama')
            ->where('jabatan_utama.status', '1') // Hanya jabatan yang aktif
            ->groupBy('jabatan_utama.kode', 'jabatan_utama.nama_jabatan_utama')
            ->having('total_count', '>', 0); // Hanya tampilkan jabatan yang memiliki pegawai
    }

    /**
     * Mendapatkan total pegawai untuk perhitungan persentase
     *
     * @return int
     */
    protected function getTotalEmployees(): int
    {
        return DataPegawai::count();
    }

    /**
     * Polling interval untuk refresh otomatis (dalam detik)
     *
     * @var string|null
     */
    protected static ?string $pollingInterval = '30s';

    /**
     * Dapat di-refresh secara manual
     *
     * @var bool
     */
    protected static bool $isLazy = false;

    /**
     * Mendapatkan key unik untuk setiap record dalam tabel
     *
     * @param mixed $record
     * @return string
     */
    public function getTableRecordKey($record): string
    {
        // Pastikan selalu mengembalikan string, gunakan kode jabatan sebagai identifier
        return (string) ($record->kode ?? 'unknown-' . uniqid());
    }
}