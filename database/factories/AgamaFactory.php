<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agama>
 */
class AgamaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $agama = [
            'Islam',
            'Kristen Katolik',
            'Kristen Protestan',
            'Hindu',
            'Budha',
            'Konghucu',
            'kepercayaan'
        ];

        return [
            'kode' => str_pad($this->faker->unique()->numberBetween(1, int2: 999), 3, '0', STR_PAD_LEFT),
            'nama_agama' => $this->faker->randomElement($agama),
            'status' => $this->faker->randomElement(['1', '0']),
            'urut' => $this->faker->numberBetween(1, 100),
        ];
    }
}
