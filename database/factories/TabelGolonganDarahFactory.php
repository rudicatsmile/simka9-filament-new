<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TabelGolonganDarah>
 */
class TabelGolonganDarahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $golonganDarah = ['A', 'B', 'AB', 'O'];
        
        return [
            'kode' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{2}'),
            'nama_golongan_darah' => $this->faker->randomElement($golonganDarah),
            'status' => $this->faker->randomElement(['1', '0']),
            'urut' => $this->faker->numberBetween(1, 10),
        ];
    }
}
