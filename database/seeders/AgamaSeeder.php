<?php

namespace Database\Seeders;

use App\Models\Agama;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //call the AgamaFactory to create 10 records
        // \App\Models\Agama::factory(10)->create();
        $agama = [
            ['kode' => '001', 'nama_agama' => 'Islam', 'status' => '1', 'urut' => 1],
            ['kode' => '002', 'nama_agama' => 'Kristen Protestan', 'status' => '1', 'urut' => 2],
            ['kode' => '003', 'nama_agama' => 'Kristen Katolik', 'status' => '1', 'urut' => 3],
            ['kode' => '004', 'nama_agama' => 'Budha', 'status' => '1', 'urut' => 4],
            ['kode' => '005', 'nama_agama' => 'Hindu', 'status' => '1', 'urut' => 5],
            ['kode' => '006', 'nama_agama' => 'Konghucu', 'status' => '1', 'urut' => 6],
            ['kode' => '007', 'nama_agama' => 'Kepercayaan', 'status' => '1', 'urut' => 7],
        ];

        foreach ($agama as $data) {
            Agama::create($data);
        }
    }
}
