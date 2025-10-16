<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DataPegawai;

class DataPegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample data pegawai with specific data
        $dataPegawai = [
            [
                'nip' => '198501012010011001',
                'nik' => '3201010101850001',
                'kode_unit_kerja' => '001',
                'bpjs' => '0001234567890',
                'npwp' => '123456789012345',
                'nuptk' => '1234567890123456',
                'nama_lengkap' => 'Dr. Ahmad Suryadi, M.Pd',
                'password' => 'password123',
                'tmp_lahir' => 'Jakarta',
                'tgl_lahir' => '1985-01-01',
                'jns_kelamin' => '1',
                'kode_agama' => '001',
                'kode_golongan_darah' => '001',
                'kode_status_nikah' => '002',
                'pstatus' => '1',
                'kode_status_kepegawaian' => '001',
                'blokir' => 'Tidak',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'kode_propinsi' => '31',
                'kodepos' => 10110,
                'email' => 'ahmad.suryadi@example.com',
                'no_tlp' => '081234567890',
                'tinggi_badan' => 175,
                'berat_badan' => 70,
                'kode_jabatan_utama' => '001.002',
                'unit_fungsi' => 'Kepala Sekolah',
                'unit_tugas' => 'Administrasi',
                'unit_pelajaran' => 'Matematika',
                'mulai_bekerja' => '2010-01-01',
                'kode_jenjang_pendidikan' => '005',
                'program_studi' => 'Pendidikan Matematika',
                'nama_kampus' => 'Universitas Indonesia',
                'tahun_lulus' => '2008',
                'createdon' => now(),
                'createdby' => 'system',
            ],

        ];

        foreach ($dataPegawai as $data) {
            DataPegawai::create($data);
        }

        // Create additional random data
        //DataPegawai::factory()->count(7)->create();
    }
}
