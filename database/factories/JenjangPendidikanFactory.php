<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenjangPendidikan>
 */
class JenjangPendidikanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenjangOptions = [
            ['kode' => 'SD', 'nama' => 'Sekolah Dasar'],
            ['kode' => 'SMP', 'nama' => 'Sekolah Menengah Pertama'],
            ['kode' => 'SMA', 'nama' => 'Sekolah Menengah Atas'],
            ['kode' => 'D3', 'nama' => 'Diploma 3'],
            ['kode' => 'S1', 'nama' => 'Sarjana (S1)'],
            ['kode' => 'S2', 'nama' => 'Magister (S2)'],
            ['kode' => 'S3', 'nama' => 'Doktor (S3)'],
        ];

        $selected = $this->faker->randomElement($jenjangOptions);

        return [
            'kode' => $selected['kode'],
            'nama_jenjang_pendidikan' => $selected['nama'],
            'status' => $this->faker->randomElement(['1', '0']),
            'urut' => $this->faker->numberBetween(1, 10),
        ];
    }
}
