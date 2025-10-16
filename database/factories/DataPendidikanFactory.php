<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataPendidikan>
 */
class DataPendidikanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nip_pegawai' => \App\Models\DataPegawai::factory(),
            'jenjang_pendidikan' => $this->faker->randomElement(['SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3']),
            'nama_sekolah' => $this->faker->company() . ' ' . $this->faker->randomElement(['School', 'University', 'Institute', 'Academy']),
            'jurusan' => $this->faker->randomElement(['Teknik Informatika', 'Manajemen', 'Akuntansi', 'Hukum', 'Kedokteran', 'Ekonomi', 'Pendidikan', 'Teknik Sipil']),
            'tahun_lulus' => $this->faker->numberBetween(1990, 2023),
            'nilai_ijazah' => $this->faker->randomFloat(2, 2.5, 4.0),
            'nomor_ijazah' => $this->faker->numerify('###/###/####'),
            'status' => $this->faker->randomElement(['1', '0']),
            'urut' => $this->faker->numberBetween(1, 100),
        ];
    }
}
