<?php

namespace Database\Factories;

use App\Models\DataPegawai;
use App\Models\TabelHubunganKeluarga;
use App\Models\JenjangPendidikan;
use App\Models\TabelPekerjaan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for DataRiwayatAnak model
 * 
 * Generates realistic test data for employee children records
 * with proper relationships and varied data distribution.
 * 
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataRiwayatAnak>
 * @author Laravel Filament
 * @version 1.0.0
 */
class DataRiwayatAnakFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate realistic Indonesian names
        $firstNames = [
            'Ahmad', 'Siti', 'Muhammad', 'Nur', 'Budi', 'Sri', 'Andi', 'Dewi',
            'Rizki', 'Maya', 'Fajar', 'Indira', 'Arif', 'Lestari', 'Dimas', 'Putri'
        ];
        
        $lastNames = [
            'Santoso', 'Wijaya', 'Pratama', 'Sari', 'Putra', 'Wati', 'Kusuma', 'Fitri',
            'Ramadhan', 'Anggraini', 'Setiawan', 'Maharani', 'Hidayat', 'Permata'
        ];

        $cities = [
            'Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang', 'Makassar',
            'Palembang', 'Tangerang', 'Depok', 'Bekasi', 'Bogor', 'Yogyakarta'
        ];

        $birthDate = $this->faker->dateTimeBetween('-25 years', '-1 year');
        $gender = $this->faker->randomElement(['L', 'P']);
        
        return [
            'nik_data_pegawai' => $this->faker->unique()->numerify('##########'),
            'nama_anak' => $this->faker->randomElement($firstNames) . ' ' . $this->faker->randomElement($lastNames),
            'tempat_lahir' => $this->faker->randomElement($cities),
            'tanggal_lahir' => $birthDate,
            'jenis_kelamin' => $gender,
            'kode_tabel_hubungan_keluarga' => TabelHubunganKeluarga::inRandomOrder()->first()?->kode ?? TabelHubunganKeluarga::factory()->create()->kode,
            'kode_jenjang_pendidikan' => $this->getEducationLevel($birthDate),
            'kode_tabel_pekerjaan' => $this->getProfession($birthDate),
            'urut' => $this->faker->numberBetween(1, 10),
        ];
    }

    /**
     * Get random education level based on age
     *
     * @param \DateTime $birthDate
     * @return string|null
     */
    private function getEducationLevel(\DateTime $birthDate): ?string
    {
        $age = (new \DateTime())->diff($birthDate)->y;
        
        if ($age < 6) {
            return null; // Too young for formal education
        } elseif ($age >= 6 && $age < 12) {
            return JenjangPendidikan::where('nama_jenjang_pendidikan', 'LIKE', '%TK%')->first()?->kode ?? null;
        } elseif ($age >= 12 && $age < 15) {
            return JenjangPendidikan::where('nama_jenjang_pendidikan', 'LIKE', '%SD%')->first()?->kode ?? null;
        } elseif ($age >= 15 && $age < 18) {
            return JenjangPendidikan::where('nama_jenjang_pendidikan', 'LIKE', '%SMP%')->first()?->kode ?? null;
        } elseif ($age >= 18 && $age < 22) {
            return JenjangPendidikan::where('nama_jenjang_pendidikan', 'LIKE', '%SMA%')->first()?->kode ?? null;
        } else {
            // For adults, randomly choose between D3, S1, S2
            $levels = ['D3', 'S1', 'S2'];
            $level = $this->faker->randomElement($levels);
            return JenjangPendidikan::where('nama_jenjang_pendidikan', 'LIKE', "%{$level}%")->first()?->kode ?? null;
        }
    }

    /**
     * Get profession based on age
     *
     * @param \DateTime $birthDate
     * @return string|null
     */
    private function getProfession(\DateTime $birthDate): ?string
    {
        $age = (new \DateTime())->diff($birthDate)->y;
        
        if ($age < 17) {
            return TabelPekerjaan::where('nama_pekerjaan', 'LIKE', '%Pelajar%')->first()?->kode ?? 
                   TabelPekerjaan::where('nama_pekerjaan', 'LIKE', '%Siswa%')->first()?->kode ?? null;
        } else {
            return TabelPekerjaan::inRandomOrder()->first()?->kode ?? null;
        }
    }

    /**
     * Create a male child
     *
     * @return static
     */
    public function male(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_kelamin' => 'L',
        ]);
    }

    /**
     * Create a female child
     *
     * @return static
     */
    public function female(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_kelamin' => 'P',
        ]);
    }

    /**
     * Create a factory instance with a specific sequence number for urut field.
     *
     * @param int $sequence
     * @return static
     */
    public function withSequence(int $sequence): static
    {
        return $this->state(fn (array $attributes) => [
            'urut' => $sequence,
        ]);
    }

    /**
     * Create a young child (under 10 years old)
     *
     * @return static
     */
    public function young(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal_lahir' => $this->faker->dateTimeBetween('-10 years', '-1 year'),
            'id_jenjang_pendidikan' => $this->faker->optional(0.7)->passthrough(
                JenjangPendidikan::whereIn('nama_jenjang_pendidikan', ['TK', 'SD', 'SMP'])->inRandomOrder()->first()?->id
            ),
            'id_tabel_pekerjaan' => null,
        ]);
    }

    /**
     * Create an adult child (over 18 years old)
     *
     * @return static
     */
    public function adult(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal_lahir' => $this->faker->dateTimeBetween('-25 years', '-18 years'),
            'id_jenjang_pendidikan' => JenjangPendidikan::whereIn('nama_jenjang_pendidikan', ['SMA', 'D3', 'S1', 'S2'])->inRandomOrder()->first()?->id,
            'id_tabel_pekerjaan' => TabelPekerjaan::inRandomOrder()->first()?->id,
        ]);
    }

    /**
     * Create a child for a specific employee
     *
     * @param string $nikPegawai
     * @return static
     */
    public function forPegawai(string $nikPegawai): static
    {
        return $this->state(fn (array $attributes) => [
            'nik_data_pegawai' => $nikPegawai,
        ]);
    }
}
