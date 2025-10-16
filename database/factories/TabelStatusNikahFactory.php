<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TabelStatusNikah>
 */
class TabelStatusNikahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statusNikah = [
            ['kode' => 'BM', 'nama' => 'Belum Menikah'],
            ['kode' => 'M', 'nama' => 'Menikah'],
            ['kode' => 'CH', 'nama' => 'Cerai Hidup'],
            ['kode' => 'CM', 'nama' => 'Cerai Mati'],
        ];

        $selected = $this->faker->randomElement($statusNikah);

        return [
            'kode' => $selected['kode'],
            'nama_status_nikah' => $selected['nama'],
            'status' => $this->faker->randomElement(['1', '0']),
            'urut' => $this->faker->numberBetween(1, 100),
        ];
    }
}
