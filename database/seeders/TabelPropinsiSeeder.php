<?php

namespace Database\Seeders;

use App\Models\TabelPropinsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TabelPropinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data propinsi Indonesia dengan kode dan nama yang benar
        $provinces = [
            ['kode' => '11', 'nama_propinsi' => 'Aceh', 'urut' => 1],
            ['kode' => '12', 'nama_propinsi' => 'Sumatera Utara', 'urut' => 2],
            ['kode' => '13', 'nama_propinsi' => 'Sumatera Barat', 'urut' => 3],
            ['kode' => '14', 'nama_propinsi' => 'Riau', 'urut' => 4],
            ['kode' => '15', 'nama_propinsi' => 'Jambi', 'urut' => 5],
            ['kode' => '16', 'nama_propinsi' => 'Sumatera Selatan', 'urut' => 6],
            ['kode' => '17', 'nama_propinsi' => 'Bengkulu', 'urut' => 7],
            ['kode' => '18', 'nama_propinsi' => 'Lampung', 'urut' => 8],
            ['kode' => '19', 'nama_propinsi' => 'Kepulauan Bangka Belitung', 'urut' => 9],
            ['kode' => '21', 'nama_propinsi' => 'Kepulauan Riau', 'urut' => 10],
            ['kode' => '31', 'nama_propinsi' => 'DKI Jakarta', 'urut' => 11],
            ['kode' => '32', 'nama_propinsi' => 'Jawa Barat', 'urut' => 12],
            ['kode' => '33', 'nama_propinsi' => 'Jawa Tengah', 'urut' => 13],
            ['kode' => '34', 'nama_propinsi' => 'DI Yogyakarta', 'urut' => 14],
            ['kode' => '35', 'nama_propinsi' => 'Jawa Timur', 'urut' => 15],
            ['kode' => '36', 'nama_propinsi' => 'Banten', 'urut' => 16],
            ['kode' => '51', 'nama_propinsi' => 'Bali', 'urut' => 17],
            ['kode' => '52', 'nama_propinsi' => 'Nusa Tenggara Barat', 'urut' => 18],
            ['kode' => '53', 'nama_propinsi' => 'Nusa Tenggara Timur', 'urut' => 19],
            ['kode' => '61', 'nama_propinsi' => 'Kalimantan Barat', 'urut' => 20],
            ['kode' => '62', 'nama_propinsi' => 'Kalimantan Tengah', 'urut' => 21],
            ['kode' => '63', 'nama_propinsi' => 'Kalimantan Selatan', 'urut' => 22],
            ['kode' => '64', 'nama_propinsi' => 'Kalimantan Timur', 'urut' => 23],
            ['kode' => '65', 'nama_propinsi' => 'Kalimantan Utara', 'urut' => 24],
            ['kode' => '71', 'nama_propinsi' => 'Sulawesi Utara', 'urut' => 25],
            ['kode' => '72', 'nama_propinsi' => 'Sulawesi Tengah', 'urut' => 26],
            ['kode' => '73', 'nama_propinsi' => 'Sulawesi Selatan', 'urut' => 27],
            ['kode' => '74', 'nama_propinsi' => 'Sulawesi Tenggara', 'urut' => 28],
            ['kode' => '75', 'nama_propinsi' => 'Gorontalo', 'urut' => 29],
            ['kode' => '76', 'nama_propinsi' => 'Sulawesi Barat', 'urut' => 30],
            ['kode' => '81', 'nama_propinsi' => 'Maluku', 'urut' => 31],
            ['kode' => '82', 'nama_propinsi' => 'Maluku Utara', 'urut' => 32],
            ['kode' => '91', 'nama_propinsi' => 'Papua Barat', 'urut' => 33],
            ['kode' => '94', 'nama_propinsi' => 'Papua', 'urut' => 34],
        ];

        // Insert data propinsi ke database
        foreach ($provinces as $province) {
            TabelPropinsi::create([
                'kode' => $province['kode'],
                'nama_propinsi' => $province['nama_propinsi'],
                'status' => '1', // Active by default
                'urut' => $province['urut'],
            ]);
        }
    }
}
