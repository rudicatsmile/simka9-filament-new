<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitKerjaData = [
            ['kode' => '001', 'nama_unit_kerja' => 'Yayasan', 'status' => '1', 'urut' => 1],
            ['kode' => '002', 'nama_unit_kerja' => 'TK Al Wathoniyah 9', 'status' => '1', 'urut' => 2],
            ['kode' => '003', 'nama_unit_kerja' => 'SD Al Wathoniyah 9', 'status' => '1', 'urut' => 3],
            ['kode' => '004', 'nama_unit_kerja' => 'SMP Al Wathoniyah 9', 'status' => '1', 'urut' => 4],
            ['kode' => '005', 'nama_unit_kerja' => 'SMK Dinamika Pembangunan 1 Jakarta', 'status' => '1', 'urut' => 5],
            ['kode' => '006', 'nama_unit_kerja' => 'SMK Dinamika Pembangunan 2 Jakarta', 'status' => '1', 'urut' => 6],
            ['kode' => '007', 'nama_unit_kerja' => 'Tenaga Kependidikan Yayasan', 'status' => '1', 'urut' => 7],
            ['kode' => '008', 'nama_unit_kerja' => 'Pelatih', 'status' => '1', 'urut' => 8],
            ['kode' => '009', 'nama_unit_kerja' => 'Maintenance', 'status' => '1', 'urut' => 9],
            ['kode' => '010', 'nama_unit_kerja' => 'Satuan Pengamanan', 'status' => '1', 'urut' => 10],
            ['kode' => '011', 'nama_unit_kerja' => 'Kebersihan', 'status' => '1', 'urut' => 11],
            ['kode' => '012', 'nama_unit_kerja' => 'Koperasi An-Nas', 'status' => '1', 'urut' => 12],
        ];

        foreach ($unitKerjaData as $data) {
            \App\Models\UnitKerja::create($data);
        }
    }
}
