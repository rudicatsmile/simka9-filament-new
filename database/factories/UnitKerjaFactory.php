<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UnitKerja>
 */
class UnitKerjaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitKerjaOptions = [
            ['kode' => 'KEPSEK', 'nama' => 'Kepala Sekolah'],
            ['kode' => 'WAKEPSEK', 'nama' => 'Wakil Kepala Sekolah'],
            ['kode' => 'GURU', 'nama' => 'Guru'],
            ['kode' => 'WALI', 'nama' => 'Wali Kelas'],
            ['kode' => 'TU', 'nama' => 'Tata Usaha'],
            ['kode' => 'PERPUS', 'nama' => 'Perpustakaan'],
            ['kode' => 'LAB', 'nama' => 'Laboratorium'],
        ];

        $selected = $this->faker->randomElement($unitKerjaOptions);

        return [
            'kode' => $selected['kode'],
            'nama_unit_kerja' => $selected['nama'],
            'status' => $this->faker->randomElement(['1', '0']),
            'urut' => $this->faker->numberBetween(1, 10),
        ];
    }
}
