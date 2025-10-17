<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RiwayatPendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa pegawai yang sudah ada untuk dijadikan referensi
        $pegawais = \App\Models\DataPegawai::limit(10)->get();

        foreach ($pegawais as $pegawai) {
            // Buat 1-3 riwayat pendidikan untuk setiap pegawai
            \App\Models\RiwayatPendidikan::factory()->count(rand(1, 3))->create([
                'nik_data_pegawai' => $pegawai->nik,
                'kode_jenjang_pendidikan' => '006',
            ]);
        }
    }
}
