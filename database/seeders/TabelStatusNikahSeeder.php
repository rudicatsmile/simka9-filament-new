<?php

namespace Database\Seeders;

use App\Models\TabelStatusNikah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TabelStatusNikahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusNikahData = [
            [
                'kode' => '001',
                'nama_status_nikah' => 'Belum Menikah',
                'status' => '1',
                'urut' => 1,
            ],
            [
                'kode' => '002',
                'nama_status_nikah' => 'Menikah',
                'status' => '1',
                'urut' => 2,
            ],
            [
                'kode' => '003',
                'nama_status_nikah' => 'Cerai Hidup',
                'status' => '1',
                'urut' => 3,
            ],
            [
                'kode' => '004',
                'nama_status_nikah' => 'Cerai Mati',
                'status' => '1',
                'urut' => 4,
            ],
        ];

        foreach ($statusNikahData as $data) {
            TabelStatusNikah::create($data);
        }
    }
}
