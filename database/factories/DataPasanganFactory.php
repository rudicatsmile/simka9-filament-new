<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataPasangan>
 */
class DataPasanganFactory extends Factory
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
            'nama_pasangan' => $this->faker->name(),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->dateTimeBetween('-60 years', '-20 years')->format('Y-m-d'),
            'pekerjaan' => $this->faker->jobTitle(),
            'alamat' => $this->faker->address(),
            'telepon' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement(['1', '0']),
            'urut' => $this->faker->numberBetween(1, 100),
        ];
    }
}
