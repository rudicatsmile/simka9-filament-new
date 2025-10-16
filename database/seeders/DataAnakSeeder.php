<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DataAnak;
use App\Models\DataPegawai;

class DataAnakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa pegawai yang sudah ada untuk dijadikan referensi
        $pegawais = DataPegawai::limit(10)->get();
        
        foreach ($pegawais as $pegawai) {
            // Buat 0-3 data anak untuk setiap pegawai
            DataAnak::factory()->count(rand(0, 3))->create([
                'nip_pegawai' => $pegawai->nip
            ]);
        }
    }
}
