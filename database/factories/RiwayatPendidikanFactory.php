<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RiwayatPendidikan>
 */
class RiwayatPendidikanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sekolahOptions = [
            'SD Negeri 1 Jakarta',
            'SD Negeri 2 Bandung',
            'SMP Negeri 1 Surabaya',
            'SMP Negeri 3 Medan',
            'SMA Negeri 1 Jakarta',
            'SMA Negeri 2 Bandung',
            'SMK Negeri 1 Yogyakarta',
            'Universitas Indonesia',
            'Institut Teknologi Bandung',
            'Universitas Gadjah Mada',
            'Universitas Padjadjaran',
            'Institut Teknologi Sepuluh Nopember',
            'Universitas Airlangga',
            'Universitas Brawijaya',
            'Politeknik Negeri Jakarta',
            'Politeknik Negeri Bandung',
        ];

        $jenjangOptions = ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'];

        return [
            'nik_data_pegawai' => \App\Models\DataPegawai::factory(),
            'kode_jenjang_pendidikan' => $this->faker->randomElement($jenjangOptions),
            'nama_sekolah' => $this->faker->randomElement($sekolahOptions),
            'tahun_ijazah' => $this->faker->year($max = 'now'),
            'urut' => $this->faker->numberBetween(1, 5),
        ];
    }
}
