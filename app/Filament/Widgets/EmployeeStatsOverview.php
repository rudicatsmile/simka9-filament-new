<?php

namespace App\Filament\Widgets;

use App\Models\DataPegawai;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * EmployeeStatsOverview Widget
 *
 * Widget untuk menampilkan statistik pegawai di dashboard.
 * Menampilkan total pegawai, pegawai pria, dan pegawai wanita.
 *
 * @package App\Filament\Widgets
 * @author Laravel Filament
 * @version 1.0.0
 */
class EmployeeStatsOverview extends BaseWidget
{
    /**
     * Mendapatkan statistik pegawai
     *
     * @return array
     */
    protected function getStats(): array
    {
        // Query untuk mendapatkan statistik pegawai
        $totalPegawai = DataPegawai::count();
        $pegawaiPria = DataPegawai::where('jns_kelamin', '1')->count();
        $pegawaiWanita = DataPegawai::where('jns_kelamin', '0')->count();
        // dd($totalPegawai, $pegawaiPria, $pegawaiWanita);
        return [
            Stat::make('Total Pegawai', number_format($totalPegawai))
                ->description('Jumlah seluruh pegawai')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->icon('heroicon-o-users'),

            Stat::make('Pegawai Pria', number_format($pegawaiPria))
                ->description('Jumlah pegawai laki-laki')
                ->descriptionIcon('heroicon-m-user')
                ->color('info')
                ->icon('heroicon-o-user'),

            Stat::make('Pegawai Wanita', number_format($pegawaiWanita))
                ->description('Jumlah pegawai perempuan')
                ->descriptionIcon('heroicon-m-user')
                ->color('success')
                ->icon('heroicon-o-user'),
        ];
    }

    /**
     * Mengatur kolom grid untuk widget
     *
     * @return int
     */
    protected function getColumns(): int
    {
        return 3;
    }

    /**
     * Mengatur polling interval untuk refresh otomatis
     *
     * @return string|null
     */
    protected function getPollingInterval(): ?string
    {
        return '30s'; // Refresh setiap 30 detik
    }
}
