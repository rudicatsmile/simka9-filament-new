<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TabelPekerjaan;

/**
 * Seeder untuk TabelPekerjaan
 *
 * Seeder ini mengisi tabel tabel_pekerjaan dengan data dummy
 */
class TabelPekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $pekerjaan = [
            ['kode' => '001', 'nama_pekerjaan' => 'Pegawai Negeri Sipil', 'status' => '1', 'urut' => 1],
            ['kode' => '002', 'nama_pekerjaan' => 'Karyawan Swasta', 'status' => '1', 'urut' => 2],
            ['kode' => '003', 'nama_pekerjaan' => 'Wiraswasta', 'status' => '1', 'urut' => 3],
            ['kode' => '004', 'nama_pekerjaan' => 'Petani', 'status' => '1', 'urut' => 4],
            ['kode' => '005', 'nama_pekerjaan' => 'Nelayan', 'status' => '1', 'urut' => 5],
            ['kode' => '006', 'nama_pekerjaan' => 'Guru', 'status' => '1', 'urut' => 6],
            ['kode' => '007', 'nama_pekerjaan' => 'Dokter', 'status' => '1', 'urut' => 7],
            ['kode' => '008', 'nama_pekerjaan' => 'Perawat', 'status' => '1', 'urut' => 8],
            ['kode' => '009', 'nama_pekerjaan' => 'Polisi', 'status' => '1', 'urut' => 9],
            ['kode' => '010', 'nama_pekerjaan' => 'TNI', 'status' => '1', 'urut' => 10],
        ];

        foreach ($pekerjaan as $data) {
            TabelPekerjaan::create($data);
        }
    }
}
