<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TabelJenisPelatihan;

/**
 * TabelJenisPelatihanSeeder
 *
 * Seeder untuk mengisi data dummy jenis pelatihan
 *
 * @package Database\Seeders
 * @author Laravel Filament
 * @version 1.0.0
 */
class TabelJenisPelatihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisPelatihan = [
            ['kode' => '001', 'nama_jenis_pelatihan' => 'Pelatihan Teknis', 'status' => '1', 'urut' => 1],
            ['kode' => '002', 'nama_jenis_pelatihan' => 'Pelatihan Manajemen', 'status' => '1', 'urut' => 2],
            ['kode' => '003', 'nama_jenis_pelatihan' => 'Pelatihan Kepemimpinan', 'status' => '1', 'urut' => 3],
            ['kode' => '004', 'nama_jenis_pelatihan' => 'Pelatihan IT', 'status' => '1', 'urut' => 4],
            ['kode' => '005', 'nama_jenis_pelatihan' => 'Pelatihan Bahasa', 'status' => '1', 'urut' => 5],
            ['kode' => '006', 'nama_jenis_pelatihan' => 'Pelatihan Keuangan', 'status' => '1', 'urut' => 6],
            ['kode' => '007', 'nama_jenis_pelatihan' => 'Pelatihan SDM', 'status' => '1', 'urut' => 7],
            ['kode' => '008', 'nama_jenis_pelatihan' => 'Pelatihan Marketing', 'status' => '1', 'urut' => 8],
            ['kode' => '009', 'nama_jenis_pelatihan' => 'Pelatihan Operasional', 'status' => '1', 'urut' => 9],
            ['kode' => '010', 'nama_jenis_pelatihan' => 'Pelatihan Keselamatan Kerja', 'status' => '1', 'urut' => 10],
            ['kode' => '011', 'nama_jenis_pelatihan' => 'Pelatihan Komunikasi', 'status' => '1', 'urut' => 11],
            ['kode' => '012', 'nama_jenis_pelatihan' => 'Pelatihan Administrasi', 'status' => '1', 'urut' => 12],
            ['kode' => '013', 'nama_jenis_pelatihan' => 'Pelatihan Pelayanan', 'status' => '1', 'urut' => 13],
            ['kode' => '014', 'nama_jenis_pelatihan' => 'Pelatihan Audit', 'status' => '1', 'urut' => 14],
            ['kode' => '015', 'nama_jenis_pelatihan' => 'Pelatihan Hukum', 'status' => '1', 'urut' => 15],
        ];

        foreach ($jenisPelatihan as $data) {
            TabelJenisPelatihan::create($data);
        }
    }
}
