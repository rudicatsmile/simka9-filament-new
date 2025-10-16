<?php

namespace Database\Seeders;

use App\Models\JabatanUtama;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeder untuk tabel jabatan_utama
 *
 * Seeder ini akan mengisi tabel jabatan_utama dengan data sample
 * yang sesuai dengan struktur organisasi sekolah
 */
class JabatanUtamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $jabatanUtamaData = [
            ['kode' => '001.001', 'kode_unit_kerja' => '001', 'nama_jabatan_utama' => 'Kepala Bagian', 'status' => '1', 'urut' => 1],
            ['kode' => '001.002', 'kode_unit_kerja' => '001', 'nama_jabatan_utama' => 'Kepala Divisi', 'status' => '1', 'urut' => 2],
            ['kode' => '001.003', 'kode_unit_kerja' => '001', 'nama_jabatan_utama' => 'Kasubag', 'status' => '1', 'urut' => 3],
            ['kode' => '001.004', 'kode_unit_kerja' => '001', 'nama_jabatan_utama' => 'Konsultan Pendidikan', 'status' => '1', 'urut' => 4],
            ['kode' => '001.005', 'kode_unit_kerja' => '001', 'nama_jabatan_utama' => 'Staf Yayasan', 'status' => '1', 'urut' => 5],
        ];

        foreach ($jabatanUtamaData as $data) {
            JabatanUtama::create($data);
        }
    }
}
