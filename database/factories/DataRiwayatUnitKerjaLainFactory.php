<?php

namespace Database\Factories;

use App\Models\DataRiwayatUnitKerjaLain;
use App\Models\DataPegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataRiwayatUnitKerjaLain>
 */
class DataRiwayatUnitKerjaLainFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DataRiwayatUnitKerjaLain::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $instansiOptions = [
            'PT. Telkom Indonesia',
            'PT. Bank Mandiri',
            'PT. Pertamina',
            'PT. PLN (Persero)',
            'PT. Garuda Indonesia',
            'Kementerian Keuangan',
            'Kementerian Pendidikan',
            'Dinas Kesehatan Provinsi',
            'Dinas Pendidikan Kota',
            'BUMN Pelindo',
            'PT. Semen Indonesia',
            'PT. Kereta Api Indonesia',
            'Universitas Negeri Jakarta',
            'Rumah Sakit Umum Daerah',
            'Pemerintah Daerah Kabupaten',
        ];

        $jabatanOptions = [
            'Manager',
            'Supervisor',
            'Staff',
            'Koordinator',
            'Kepala Bagian',
            'Kepala Seksi',
            'Analis',
            'Konsultan',
            'Asisten Manager',
            'Team Leader',
            'Specialist',
            'Officer',
        ];

        $fungsiOptions = [
            'MNG01', 'SUP01', 'STF01', 'KOR01', 'KBG01',
            'KSK01', 'ANL01', 'KON01', 'ASM01', 'TLD01',
            'SPC01', 'OFC01', 'ADM01', 'FIN01', 'HRD01',
        ];

        $tanggalMulai = $this->faker->dateTimeBetween('-5 years', '-1 year');
        $tanggalSelesai = $this->faker->optional(0.7)->dateTimeBetween($tanggalMulai, 'now');

        return [
            'nik_data_pegawai' => DataPegawai::factory(),
            'nama_unit_kerja' => $this->faker->randomElement($instansiOptions),
            'alamat_unit_kerja' => $this->faker->address(),
            'jabatan' => $this->faker->randomElement($jabatanOptions),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'is_bekerja_di_tempat_lain' => $this->faker->boolean(),
            'status' => $this->faker->randomElement(['aktif', 'tidak_aktif', 'selesai']),
        ];
    }

    /**
     * Indicate that the employee is currently working at another place.
     */
    public function bekerjaLain(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_bekerja_di_tempat_lain' => true,
        ]);
    }

    /**
     * Indicate that the employee is not working at another place.
     */
    public function tidakBekerjaLain(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_bekerja_di_tempat_lain' => false,
        ]);
    }

    /**
     * Indicate that the employee has active status.
     */
    public function statusAktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aktif',
        ]);
    }

    /**
     * Indicate that the employee has inactive status.
     */
    public function statusTidakAktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'tidak_aktif',
        ]);
    }

    /**
     * Indicate that the employee has finished status.
     */
    public function statusSelesai(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
        ]);
    }
}