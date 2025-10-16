<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DataPasangan;
use App\Models\DataPegawai;

class DataPasanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa pegawai yang sudah ada untuk dijadikan referensi
        $pegawais = DataPegawai::limit(10)->get();
        
        foreach ($pegawais as $pegawai) {
            // Buat 1-2 data pasangan untuk setiap pegawai
            DataPasangan::factory()->count(rand(0, 1))->create([
                'nip_pegawai' => $pegawai->nip
            ]);
        }
    }
}
