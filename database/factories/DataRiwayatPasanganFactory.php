<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataRiwayatPasangan>
 */
class DataRiwayatPasanganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $namaOptions = [
            'Siti Nurhaliza',
            'Dewi Sartika',
            'Kartini Putri',
            'Aisyah Rahman',
            'Fatimah Zahra',
            'Khadijah Aminah',
            'Maryam Salsabila',
            'Zainab Husna',
            'Ahmad Fauzi',
            'Muhammad Rizki',
            'Abdul Rahman',
            'Umar Faruq',
            'Ali Akbar',
            'Hasan Basri',
            'Husein Alwi',
            'Yusuf Ibrahim',
        ];

        $tempatLahirOptions = [
            'Jakarta',
            'Bandung',
            'Surabaya',
            'Medan',
            'Semarang',
            'Makassar',
            'Palembang',
            'Tangerang',
            'Depok',
            'Bekasi',
            'Bogor',
            'Yogyakarta',
            'Solo',
            'Malang',
            'Denpasar',
            'Balikpapan',
        ];

        $jenjangOptions = ['004', '005', '006'];
        $pekerjaanOptions = ['006', '007', '008', '009', '010', '011'];
        $hubunganOptions = ['Suami', 'Istri'];

        return [
            'nik_data_pegawai' => \App\Models\DataPegawai::factory(),
            'nama_pasangan' => $this->faker->randomElement($namaOptions),
            'tempat_lahir' => $this->faker->randomElement($tempatLahirOptions),
            'tanggal_lahir' => $this->faker->dateTimeBetween('-60 years', '-20 years')->format('Y-m-d'),
            'hubungan' => $this->faker->randomElement($hubunganOptions),
            'kode_jenjang_pendidikan' => $this->faker->randomElement($jenjangOptions),
            'kode_tabel_pekerjaan' => $this->faker->randomElement($pekerjaanOptions),
            'urut' => $this->faker->numberBetween(1, 3),
        ];
    }

    /**
     * Indicate that the pasangan is a wife.
     */
    public function istri(): static
    {
        return $this->state(fn(array $attributes) => [
            'hubungan' => 'Istri',
            'nama_pasangan' => $this->faker->randomElement([
                'Siti Nurhaliza',
                'Dewi Sartika',
                'Kartini Putri',
                'Aisyah Rahman',
                'Fatimah Zahra',
                'Khadijah Aminah',
                'Maryam Salsabila',
                'Zainab Husna',
            ]),
        ]);
    }

    /**
     * Indicate that the pasangan is a husband.
     */
    public function suami(): static
    {
        return $this->state(fn(array $attributes) => [
            'hubungan' => 'Suami',
            'nama_pasangan' => $this->faker->randomElement([
                'Ahmad Fauzi',
                'Muhammad Rizki',
                'Abdul Rahman',
                'Umar Faruq',
                'Ali Akbar',
                'Hasan Basri',
                'Husein Alwi',
                'Yusuf Ibrahim',
            ]),
        ]);
    }

    /**
     * Indicate that the pasangan has higher education.
     */
    public function berpendidikanTinggi(): static
    {
        return $this->state(fn(array $attributes) => [
            'kode_jenjang_pendidikan' => $this->faker->randomElement(['S1', 'S2', 'S3']),
        ]);
    }

    /**
     * Indicate that the pasangan works in government sector.
     */
    public function pegawaiNegeri(): static
    {
        return $this->state(fn(array $attributes) => [
            'kode_tabel_pekerjaan' => $this->faker->randomElement(['PNS', 'TNI', 'POLRI']),
        ]);
    }
}
