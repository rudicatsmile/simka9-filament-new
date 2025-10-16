<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TabelHubunganKeluarga;

class TabelHubunganKeluargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hubunganKeluarga = [
            ['kode' => '001', 'nama_hubungan_keluarga' => 'Ayah', 'status' => '1', 'urut' => 1],
            ['kode' => '002', 'nama_hubungan_keluarga' => 'Ibu', 'status' => '1', 'urut' => 2],
            ['kode' => '003', 'nama_hubungan_keluarga' => 'Anak', 'status' => '1', 'urut' => 3],
            ['kode' => '004', 'nama_hubungan_keluarga' => 'Suami', 'status' => '1', 'urut' => 4],
            ['kode' => '005', 'nama_hubungan_keluarga' => 'Istri', 'status' => '1', 'urut' => 5],
            ['kode' => '006', 'nama_hubungan_keluarga' => 'Kakak', 'status' => '1', 'urut' => 6],
            ['kode' => '007', 'nama_hubungan_keluarga' => 'Adik', 'status' => '1', 'urut' => 7],
            ['kode' => '008', 'nama_hubungan_keluarga' => 'Kakek', 'status' => '1', 'urut' => 8],
            ['kode' => '009', 'nama_hubungan_keluarga' => 'Nenek', 'status' => '1', 'urut' => 9],
            ['kode' => '010', 'nama_hubungan_keluarga' => 'Paman', 'status' => '1', 'urut' => 10],
            ['kode' => '011', 'nama_hubungan_keluarga' => 'Bibi', 'status' => '1', 'urut' => 11],
            ['kode' => '012', 'nama_hubungan_keluarga' => 'Sepupu', 'status' => '1', 'urut' => 12],
            ['kode' => '013', 'nama_hubungan_keluarga' => 'Keponakan', 'status' => '1', 'urut' => 13],
            ['kode' => '014', 'nama_hubungan_keluarga' => 'Mertua', 'status' => '1', 'urut' => 14],
            ['kode' => '015', 'nama_hubungan_keluarga' => 'Menantu', 'status' => '1', 'urut' => 15],
        ];

        foreach ($hubunganKeluarga as $data) {
            TabelHubunganKeluarga::create($data);
        }
    }
}
