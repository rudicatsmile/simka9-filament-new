<?php

namespace Database\Factories;

use App\Models\DataPegawai;
use App\Models\DataRiwayatSertifikasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for DataRiwayatSertifikasi model
 * 
 * Generates realistic test data for employee certification records
 * with proper relationships and varied data distribution.
 * 
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataRiwayatSertifikasi>
 * @author Laravel Filament
 * @version 1.0.0
 */
class DataRiwayatSertifikasiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DataRiwayatSertifikasi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $certificationNames = [
            'Sertifikat Kompetensi Administrasi Perkantoran',
            'Sertifikat Manajemen Keuangan',
            'Sertifikat Teknologi Informasi',
            'Sertifikat Kepemimpinan',
            'Sertifikat Audit Internal',
            'Sertifikat Manajemen Proyek',
            'Sertifikat Bahasa Inggris TOEFL',
            'Sertifikat Keselamatan Kerja',
            'Sertifikat Pelayanan Publik',
            'Sertifikat Analisis Data',
        ];

        $isSertifikasi = $this->faker->randomElement(['Ya', 'Tidak']);
        $tahun = $this->faker->year($max = 'now');

        return [
            'nik_data_pegawai' => DataPegawai::factory(),
            'is_sertifikasi' => $isSertifikasi,
            'nama' => $this->faker->randomElement($certificationNames),
            'nomor' => $this->faker->numerify('CERT-####/###/####'),
            'tahun' => $tahun,
            'induk_inpasing' => $isSertifikasi === 'Ya' ? $this->faker->numerify('INP-####/###') : null,
            'sk_inpasing' => $isSertifikasi === 'Ya' ? $this->faker->numerify('SK-####/###/####') : null,
            'tahun_inpasing' => $isSertifikasi === 'Ya' ? $this->faker->year($min = $tahun, $max = 'now') : null,
            'berkas' => null, // Will be set manually when needed
            'urut' => $this->faker->numberBetween(1, 10),
        ];
    }

    /**
     * Indicate that the sertifikasi is active/certified.
     *
     * @return static
     */
    public function certified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_sertifikasi' => 'Ya',
            'induk_inpasing' => $this->faker->numerify('INP-####/###'),
            'sk_inpasing' => $this->faker->numerify('SK-####/###/####'),
            'tahun_inpasing' => $this->faker->year($min = $attributes['tahun'] ?? 2020, $max = 'now'),
        ]);
    }

    /**
     * Indicate that the sertifikasi is not certified.
     *
     * @return static
     */
    public function notCertified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_sertifikasi' => 'Tidak',
            'induk_inpasing' => null,
            'sk_inpasing' => null,
            'tahun_inpasing' => null,
        ]);
    }

    /**
     * Indicate that the sertifikasi has a berkas file.
     *
     * @return static
     */
    public function withBerkas(): static
    {
        return $this->state(fn (array $attributes) => [
            'berkas' => 'sertifikasi/' . $this->faker->uuid() . '.pdf',
        ]);
    }

    /**
     * Indicate that the sertifikasi is for a specific employee.
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
     * Indicate that the sertifikasi has a specific urut order.
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

    /**
     * Indicate that the sertifikasi is from a specific year.
     *
     * @param string $tahun
     * @return static
     */
    public function fromYear(string $tahun): static
    {
        return $this->state(fn (array $attributes) => [
            'tahun' => $tahun,
        ]);
    }
}
