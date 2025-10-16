<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TabelJenisPelatihan>
 */
class TabelJenisPelatihanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenisPelatihan = [
            'Pelatihan Teknis',
            'Pelatihan Manajemen',
            'Pelatihan Kepemimpinan',
            'Pelatihan IT',
            'Pelatihan Bahasa',
            'Pelatihan Keuangan',
            'Pelatihan SDM',
            'Pelatihan Marketing',
            'Pelatihan Operasional',
            'Pelatihan Keselamatan Kerja',
            'Pelatihan Komunikasi',
            'Pelatihan Administrasi',
            'Pelatihan Pelayanan',
            'Pelatihan Audit',
            'Pelatihan Hukum',
            'Pelatihan Kesehatan',
            'Pelatihan Lingkungan',
            'Pelatihan Inovasi',
            'Pelatihan Digital',
            'Pelatihan Soft Skills'
        ];

        return [
            'kode' => 'JP' . str_pad($this->faker->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'nama_jenis_pelatihan' => $this->faker->randomElement($jenisPelatihan),
            'status' => $this->faker->randomElement(['1', '0']),
            'urut' => $this->faker->numberBetween(1, 100),
        ];
    }
}
