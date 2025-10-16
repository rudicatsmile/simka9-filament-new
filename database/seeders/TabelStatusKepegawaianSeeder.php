<?php

namespace Database\Seeders;

use App\Models\TabelStatusKepegawaian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TabelStatusKepegawaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusKepegawaian = [
            ['kode' => '001', 'nama_status_kepegawaian' => 'PTY', 'status' => '1', 'urut' => 1],
            ['kode' => '002', 'nama_status_kepegawaian' => 'PTTY', 'status' => '1', 'urut' => 2],
            ['kode' => '003', 'nama_status_kepegawaian' => 'PKK', 'status' => '1', 'urut' => 3],
        ];

        foreach ($statusKepegawaian as $status) {
            TabelStatusKepegawaian::create($status);
        }
    }
}
