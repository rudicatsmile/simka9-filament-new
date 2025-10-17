<?php

namespace Database\Seeders;

use App\Models\DataPegawai;
use App\Models\DataRiwayatKepegawaian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * DataRiwayatKepegawaianSeeder
 *
 * Seeder untuk mengisi data awal tabel data_riwayat_kepegawaian
 */
class DataRiwayatKepegawaianSeeder extends Seeder
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

        $this->command->info('Creating data riwayat kepegawaian...');

        foreach ($pegawais as $pegawai) {
            // Create 2-3 riwayat kepegawaian records per employee
            $count = rand(2, 3);

            for ($i = 1; $i <= $count; $i++) {
                DataRiwayatKepegawaian::create([
                    'nik_data_pegawai' => $pegawai->nik,
                    'nama' => $this->getKepegawaianName($i),
                    'tanggal_lahir' => now()->subYears(rand(25, 55))->format('Y-m-d'),
                    'nomor' => $this->generateNomor($pegawai->nik, $i),
                    'keterangan' => $this->getKeterangan($i),
                    'berkas' => null, // Will be set when files are uploaded
                    'urut' => $i,
                ]);
            }
        }

        $this->command->info('Data riwayat kepegawaian created successfully.');
    }

    /**
     * Generate kepegawaian name based on sequence
     *
     * @param int $sequence
     * @return string
     */
    private function getKepegawaianName(int $sequence): string
    {
        $names = [
            1 => 'Pengangkatan Pegawai',
            2 => 'Surat Tugas Sekolah',
            3 => 'Surat Perjanjian Kerjasama',
            4 => 'Mutasi Jabatan',
            5 => 'Pensiun',
        ];

        return $names[$sequence] ?? 'Riwayat Kepegawaian ' . $sequence;
    }

    /**
     * Generate nomor based on employee NIK and sequence
     *
     * @param string $nik
     * @param int $sequence
     * @return string
     */
    private function generateNomor(string $nik, int $sequence): string
    {
        $year = now()->year;
        $lastFourNik = substr($nik, -4);

        return "KEP-{$lastFourNik}/{$sequence}/{$year}";
    }

    /**
     * Get keterangan based on sequence
     *
     * @param int $sequence
     * @return string
     */
    private function getKeterangan(int $sequence): string
    {
        $keterangans = [
            1 => 'Pengangkatan sebagai  Pegawai yayasan.',
            2 => 'Pengangkatan sebagai Pegawai Yayasan setelah menyelesaikan masa percobaan.',
            3 => 'Kenaikan pangkat berkala sesuai dengan ketentuan yang berlaku.',
            4 => 'Mutasi jabatan untuk pengembangan karir dan kebutuhan organisasi.',
            5 => 'Pensiun sesuai dengan batas usia yang ditetapkan.',
        ];

        return $keterangans[$sequence] ?? 'Keterangan riwayat kepegawaian lainnya.';
    }
}
