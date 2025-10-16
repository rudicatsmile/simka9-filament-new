<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TabelHubunganKeluarga>
 */
class TabelHubunganKeluargaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hubunganKeluarga = [
            'Ayah', 'Ibu', 'Anak', 'Suami', 'Istri', 
            'Kakak', 'Adik', 'Kakek', 'Nenek', 'Paman',
            'Bibi', 'Sepupu', 'Keponakan', 'Mertua', 'Menantu'
        ];

        return [
            'kode' => $this->faker->unique()->regexify('[HK][0-9]{3}'),
            'nama_hubungan_keluarga' => $this->faker->unique()->randomElement($hubunganKeluarga),
            'status' => $this->faker->randomElement(['1', '0']),
            'urut' => $this->faker->numberBetween(1, 15),
        ];
    }
}
