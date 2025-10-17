<?php

namespace Database\Factories;

use App\Models\DataPegawai;
use App\Models\DataRiwayatKepegawaian;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataRiwayatKepegawaian>
 */
class DataRiwayatKepegawaianFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DataRiwayatKepegawaian::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nik_data_pegawai' => DataPegawai::factory(),
            'nama' => $this->faker->name(),
            'tanggal_lahir' => $this->faker->dateTimeBetween('-60 years', '-20 years')->format('Y-m-d'),
            'nomor' => $this->faker->numerify('KEP-####/###/####'),
            'keterangan' => $this->faker->sentence(10),
            'berkas' => null, // Will be set manually when needed
            'urut' => $this->faker->numberBetween(1, 10),
        ];
    }

    /**
     * Indicate that the riwayat kepegawaian has a berkas file.
     *
     * @return static
     */
    public function withBerkas(): static
    {
        return $this->state(fn (array $attributes) => [
            'berkas' => $this->faker->uuid() . '.pdf',
        ]);
    }

    /**
     * Indicate that the riwayat kepegawaian is for a specific employee.
     *
     * @param string $nik
     * @return static
     */
    public function forEmployee(string $nik): static
    {
        return $this->state(fn (array $attributes) => [
            'nik_data_pegawai' => $nik,
        ]);
    }

    /**
     * Indicate that the riwayat kepegawaian has a specific urut order.
     *
     * @param int $urut
     * @return static
     */
    public function withUrut(int $urut): static
    {
        return $this->state(fn (array $attributes) => [
            'urut' => $urut,
        ]);
    }
}