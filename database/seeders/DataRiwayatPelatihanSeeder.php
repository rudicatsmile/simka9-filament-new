<?php

namespace Database\Seeders;

use App\Models\DataPegawai;
use App\Models\DataRiwayatPelatihan;
use App\Models\TabelJenisPelatihan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataRiwayatPelatihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa pegawai yang sudah ada untuk dijadikan referensi
        $pegawais = DataPegawai::limit(15)->get();
        
        // Ambil jenis pelatihan yang sudah ada
        $jenisPelatihan = TabelJenisPelatihan::limit(5)->get();

        if ($pegawais->isEmpty()) {
            $this->command->warn('Tidak ada data pegawai. Membuat data pegawai terlebih dahulu...');
            // Buat beberapa pegawai jika belum ada
            $pegawais = DataPegawai::factory()->count(10)->create();
        }

        if ($jenisPelatihan->isEmpty()) {
            $this->command->warn('Tidak ada data jenis pelatihan. Membuat data jenis pelatihan terlebih dahulu...');
            // Buat beberapa jenis pelatihan jika belum ada
            $jenisPelatihan = TabelJenisPelatihan::factory()->count(5)->create();
        }

        $this->command->info('Membuat data riwayat pelatihan...');

        foreach ($pegawais as $pegawai) {
            // Buat 1-4 riwayat pelatihan untuk setiap pegawai
            $jumlahPelatihan = rand(1, 4);
            
            for ($i = 1; $i <= $jumlahPelatihan; $i++) {
                $jenisRandom = $jenisPelatihan->random();
                
                // Tentukan apakah pelatihan ini memiliki sertifikat (70% kemungkinan)
                $hasCertificate = rand(1, 100) <= 70;
                
                if ($hasCertificate) {
                    DataRiwayatPelatihan::factory()
                        ->withCertificate()
                        ->withSequence($i)
                        ->create([
                            'nik_data_pegawai' => $pegawai->nik,
                            'kode_tabel_jenis_pelatihan' => $jenisRandom->kode,
                        ]);
                } else {
                    DataRiwayatPelatihan::factory()
                        ->withoutCertificate()
                        ->withSequence($i)
                        ->create([
                            'nik_data_pegawai' => $pegawai->nik,
                            'kode_tabel_jenis_pelatihan' => $jenisRandom->kode,
                        ]);
                }
            }
        }

        $totalPelatihan = DataRiwayatPelatihan::count();
        $this->command->info("Berhasil membuat {$totalPelatihan} data riwayat pelatihan untuk {$pegawais->count()} pegawai.");
    }
}