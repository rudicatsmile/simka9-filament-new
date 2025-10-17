<?php

namespace Database\Seeders;

use App\Models\DataPegawai;
use App\Models\DataRiwayatSertifikasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * DataRiwayatSertifikasiSeeder
 *
 * Seeder untuk mengisi data awal tabel data_riwayat_sertifikasi
 * dengan data sertifikasi pegawai yang realistis
 * 
 * @author Laravel Filament
 * @version 1.0.0
 */
class DataRiwayatSertifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing employees
        $pegawais = DataPegawai::limit(10)->get();

        if ($pegawais->isEmpty()) {
            $this->command->warn('No employees found. Please run DataPegawaiSeeder first.');
            return;
        }

        $this->command->info('Creating data riwayat sertifikasi...');

        foreach ($pegawais as $pegawai) {
            // Create 1-3 sertifikasi records per employee
            $count = rand(1, 3);

            for ($i = 1; $i <= $count; $i++) {
                $isSertifikasi = rand(1, 10) > 3 ? 'Ya' : 'Tidak'; // 70% chance of being certified
                $tahun = now()->subYears(rand(1, 10))->year;

                DataRiwayatSertifikasi::create([
                    'nik_data_pegawai' => $pegawai->nik,
                    'is_sertifikasi' => $isSertifikasi,
                    'nama' => $this->getSertifikasiName($i),
                    'nomor' => $this->generateNomor($pegawai->nik, $i),
                    'tahun' => (string) $tahun,
                    'induk_inpasing' => $isSertifikasi === 'Ya' ? $this->generateIndukInpasing($pegawai->nik, $i) : null,
                    'sk_inpasing' => $isSertifikasi === 'Ya' ? $this->generateSkInpasing($pegawai->nik, $i) : null,
                    'tahun_inpasing' => $isSertifikasi === 'Ya' ? (string) ($tahun + rand(0, 2)) : null,
                    'berkas' => null, // Will be set when files are uploaded
                    'urut' => $i,
                ]);
            }
        }

        $this->command->info('Data riwayat sertifikasi created successfully.');
    }

    /**
     * Generate sertifikasi name based on sequence
     *
     * @param int $sequence
     * @return string
     */
    private function getSertifikasiName(int $sequence): string
    {
        $names = [
            1 => 'Sertifikat Kompetensi Administrasi Perkantoran',
            2 => 'Sertifikat Manajemen Keuangan',
            3 => 'Sertifikat Teknologi Informasi',
            4 => 'Sertifikat Kepemimpinan',
            5 => 'Sertifikat Audit Internal',
            6 => 'Sertifikat Manajemen Proyek',
            7 => 'Sertifikat Bahasa Inggris TOEFL',
            8 => 'Sertifikat Keselamatan Kerja',
            9 => 'Sertifikat Pelayanan Publik',
            10 => 'Sertifikat Analisis Data',
        ];

        return $names[$sequence] ?? $names[rand(1, 10)];
    }

    /**
     * Generate nomor sertifikat based on employee NIK and sequence
     *
     * @param string $nik
     * @param int $sequence
     * @return string
     */
    private function generateNomor(string $nik, int $sequence): string
    {
        $year = now()->year;
        $lastFourNik = substr($nik, -4);

        return "CERT-{$lastFourNik}/{$sequence}/{$year}";
    }

    /**
     * Generate induk inpasing based on employee NIK and sequence
     *
     * @param string $nik
     * @param int $sequence
     * @return string
     */
    private function generateIndukInpasing(string $nik, int $sequence): string
    {
        $lastFourNik = substr($nik, -4);
        return "INP-{$lastFourNik}/{$sequence}";
    }

    /**
     * Generate SK inpasing based on employee NIK and sequence
     *
     * @param string $nik
     * @param int $sequence
     * @return string
     */
    private function generateSkInpasing(string $nik, int $sequence): string
    {
        $year = now()->year;
        $lastFourNik = substr($nik, -4);

        return "SK-{$lastFourNik}/{$sequence}/{$year}";
    }
}
