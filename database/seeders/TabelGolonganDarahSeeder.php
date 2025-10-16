<?php

namespace Database\Seeders;

use App\Models\TabelGolonganDarah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TabelGolonganDarahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific blood type data
        $golonganDarahData = [
            ['kode' => '001', 'nama_golongan_darah' => 'A', 'status' => '1', 'urut' => 1],
            ['kode' => '002', 'nama_golongan_darah' => 'B', 'status' => '1', 'urut' => 2],
            ['kode' => '003', 'nama_golongan_darah' => 'AB', 'status' => '1', 'urut' => 3],
            ['kode' => '004', 'nama_golongan_darah' => 'O', 'status' => '1', 'urut' => 4],
        ];

        foreach ($golonganDarahData as $data) {
            TabelGolonganDarah::create($data);
        }
    }
}
