<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataRiwayatPasangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataRiwayatPasanganSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil beberapa pegawai yang sudah ada untuk dijadikan referensi
        $pegawais = \App\Models\DataPegawai::limit(15)->get();
        $jenjangOptions = ['004', '005', '006'];
        $pekerjaanOptions = ['006', '007', '008', '009', '010', '011'];
        $hubunganOptions = ['Suami', 'Istri'];

        foreach ($pegawais as $pegawai) {
            // Buat 1-2 data pasangan untuk setiap pegawai (kebanyakan orang hanya punya 1 pasangan)
            $jumlahPasangan = rand(0, 2); // 0 = belum menikah, 1-2 = punya pasangan
            $faker = \Faker\Factory::create('id_ID');
            if ($jumlahPasangan > 0) {
                DataRiwayatPasangan::insert([
                    'nik_data_pegawai' => $pegawai->nik,

                    'hubungan' => $faker->randomElement($hubunganOptions),
                    'kode_jenjang_pendidikan' => $faker->randomElement($jenjangOptions),
                    'kode_tabel_pekerjaan' => $faker->randomElement($pekerjaanOptions),
                    'urut' => 1,
                    'nama_pasangan' => $faker->name(),
                ]);
            }
        }

        // Buat beberapa data khusus untuk testing
        $pegawaiKhusus = \App\Models\DataPegawai::first();
        if ($pegawaiKhusus) {
            // Pasangan dengan pendidikan tinggi
            \App\Models\DataRiwayatPasangan::factory()->berpendidikanTinggi()->create([
                'nik_data_pegawai' => $pegawaiKhusus->nik,
                'nama_pasangan' => 'Dr. Siti Nurhaliza, M.Pd',
                'hubungan' => 'Istri',
                'urut' => 1
            ]);

            // Pasangan pegawai negeri
            \App\Models\DataRiwayatPasangan::factory()->pegawaiNegeri()->create([
                'nik_data_pegawai' => $pegawaiKhusus->nik,
                'nama_pasangan' => 'Drs. Ahmad Fauzi',
                'hubungan' => 'Suami',
                'urut' => 2
            ]);
        }
    }
}
