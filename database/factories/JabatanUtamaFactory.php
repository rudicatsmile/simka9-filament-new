<?php

namespace Database\Factories;

use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JabatanUtama>
 */
class JabatanUtamaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jabatanOptions = [
            ['kode' => 'KEPSEK01', 'nama' => 'Kepala Sekolah'],
            ['kode' => 'WAKUR01', 'nama' => 'Wakil Kepala Sekolah Bidang Kurikulum'],
            ['kode' => 'WAKESIS01', 'nama' => 'Wakil Kepala Sekolah Bidang Kesiswaan'],
            ['kode' => 'WAKA01', 'nama' => 'Wakil Kepala Sekolah Bidang Sarana Prasarana'],
            ['kode' => 'GURU01', 'nama' => 'Guru Mata Pelajaran'],
            ['kode' => 'WALI01', 'nama' => 'Wali Kelas'],
            ['kode' => 'KATATA01', 'nama' => 'Kepala Tata Usaha'],
            ['kode' => 'STAFF01', 'nama' => 'Staff Tata Usaha'],
            ['kode' => 'KAPERPUS01', 'nama' => 'Kepala Perpustakaan'],
            ['kode' => 'KALAB01', 'nama' => 'Kepala Laboratorium'],
        ];

        $selected = $this->faker->randomElement($jabatanOptions);
        
        // Get random unit kerja kode for foreign key
        $unitKerjaKode = UnitKerja::inRandomOrder()->first()?->kode ?? 'GURU';

        return [
            'kode' => $selected['kode'],
            'kode_unit_kerja' => $unitKerjaKode,
            'nama_jabatan_utama' => $selected['nama'],
            'status' => $this->faker->randomElement(['1', '0']),
            'urut' => $this->faker->numberBetween(1, 10),
        ];
    }
}
