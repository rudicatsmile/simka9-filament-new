<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TabelPekerjaan>
 */
class TabelPekerjaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pekerjaan = [
            'Pegawai Negeri Sipil',
            'Karyawan Swasta',
            'Wiraswasta',
            'Petani',
            'Nelayan',
            'Guru',
            'Dokter',
            'Perawat',
            'Polisi',
            'TNI',
            'Pengacara',
            'Akuntan',
            'Insinyur',
            'Arsitek',
            'Pilot',
            'Sopir',
            'Tukang',
            'Pedagang',
            'Buruh',
            'Pensiunan'
        ];

        return [
            'kode' => str_pad($this->faker->unique()->numberBetween(1, int2: 999), 3, '0', STR_PAD_LEFT),
            'nama_pekerjaan' => $this->faker->randomElement($pekerjaan),
            'status' => $this->faker->randomElement(['1', '0']),
            'urut' => $this->faker->numberBetween(1, 100),
        ];
    }
}
