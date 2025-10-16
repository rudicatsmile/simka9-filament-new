<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenjangPendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenjangPendidikan = [
            ['kode' => '001', 'nama_jenjang_pendidikan' => 'Belum Sekolah', 'status' => '1', 'urut' => 1],
            ['kode' => '002', 'nama_jenjang_pendidikan' => 'Sekolah Dasar', 'status' => '1', 'urut' => 2],
            ['kode' => '003', 'nama_jenjang_pendidikan' => 'Sekolah Menengah Pertama', 'status' => '1', 'urut' => 3],
            ['kode' => '004', 'nama_jenjang_pendidikan' => 'Sekolah Menengah Atas', 'status' => '1', 'urut' => 4],
            ['kode' => '005', 'nama_jenjang_pendidikan' => 'Diploma 3', 'status' => '1', 'urut' => 5],
            ['kode' => '006', 'nama_jenjang_pendidikan' => 'Sarjana (S1)', 'status' => '1', 'urut' => 6],
            ['kode' => '007', 'nama_jenjang_pendidikan' => 'Magister (S2)', 'status' => '1', 'urut' => 7],
            ['kode' => '008', 'nama_jenjang_pendidikan' => 'Doktor (S3)', 'status' => '1', 'urut' => 8],
        ];

        foreach ($jenjangPendidikan as $data) {
            \App\Models\JenjangPendidikan::create($data);
        }
    }
}
