<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataAnak>
 */
class DataAnakFactory extends Factory
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
            'nama_anak' => $this->faker->name(),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->dateTimeBetween('-25 years', '-1 years')->format('Y-m-d'),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'status_anak' => $this->faker->randomElement(['kandung', 'tiri', 'angkat']),
            'pendidikan_terakhir' => $this->faker->randomElement(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2']),
            'status' => $this->faker->randomElement(['1', '0']),
            'urut' => $this->faker->numberBetween(1, 100),
        ];
    }
}
