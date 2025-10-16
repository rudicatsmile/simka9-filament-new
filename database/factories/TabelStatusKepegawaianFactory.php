<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TabelStatusKepegawaian>
 */
class TabelStatusKepegawaianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statusKepegawaian = ['PNS', 'PPPK', 'Honorer', 'Kontrak'];
        $selectedStatus = $this->faker->randomElement($statusKepegawaian);
        
        return [
            'kode' => strtoupper(substr($selectedStatus, 0, 3)) . $this->faker->unique()->numberBetween(1, 999),
            'nama_status_kepegawaian' => $selectedStatus,
            'status' => $this->faker->randomElement(['1', '0']),
            'urut' => $this->faker->numberBetween(1, 100),
        ];
    }
}
