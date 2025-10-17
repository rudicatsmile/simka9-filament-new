<?php

namespace Database\Factories;

use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataPegawai>
 */
class DataPegawaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitKerjaKode = UnitKerja::query()
            ->whereRaw('CHAR_LENGTH(kode) <= 5')
            ->inRandomOrder()
            ->value('kode') ?? 'GURU';

        return [
            'nip' => $this->faker->unique()->numerify('####################'),
            'nik' => $this->faker->numerify('################'),
            'kode_unit_kerja' => $unitKerjaKode,
            'bpjs' => $this->faker->numerify('###########'),
            'npwp' => $this->faker->numerify('###############'),
            'nuptk' => $this->faker->numerify('################'),
            'nama_lengkap' => $this->faker->name(),
            'password' => 'password123', // Will be hashed by model mutator
            'foto' => null,
            'tmp_lahir' => $this->faker->city(),
            'tgl_lahir' => $this->faker->dateTimeBetween('-60 years', '-20 years'),
            'jns_kelamin' => $this->faker->randomElement(['1', '0']),
            'kode_agama' => $this->faker->randomElement(['001', '002', '003', '004', '005']),
            'kode_golongan_darah' => $this->faker->randomElement(['001', '002', '003', '004']),
            'kode_status_nikah' => $this->faker->randomElement(['001', '002', '003', '004']),
            'pstatus' => $this->faker->randomElement(['1', '0']),
            'kode_status_kepegawaian' => $this->faker->randomElement(['PTY', 'GTTY', 'GTY']),
            'blokir' => $this->faker->randomElement(['Tidak', 'Ya']),
            'alamat' => $this->faker->address(),
            'kode_propinsi' => $this->faker->randomElement(['31', '32', '33', '34', '35']),
            'kodepos' => $this->faker->numberBetween(10000, 99999),
            'alamat2' => $this->faker->optional()->address(),
            'kode_propinsi2' => $this->faker->optional()->randomElement(['31', '32', '33', '34', '35']),
            'kodepos2' => $this->faker->optional()->numberBetween(10000, 99999),
            'email' => $this->faker->unique()->safeEmail(),
            'no_tlp' => $this->faker->numerify('08##########'),
            'hobi' => $this->faker->optional()->words(3, true),
            'tinggi_badan' => $this->faker->numberBetween(150, 190),
            'berat_badan' => $this->faker->numberBetween(45, 100),
            'kode_jabatan_utama' => $this->faker->randomElement(['001.001', '001.002', '001.003', '001.004', '001.005']),
            'unit_fungsi' => '-',
            'unit_tugas' => '-',
            'unit_pelajaran' => '-',
            'mulai_bekerja' => $this->faker->dateTimeBetween('-30 years', '-1 year')->format('Y-m-d'),
            'kode_jenjang_pendidikan' => $this->faker->randomElement(['005', '006', '007']),
            'program_studi' => $this->faker->optional()->words(2, true),
            'nama_kampus' => $this->faker->optional()->company(),
            'tahun_lulus' => $this->faker->optional()->year(),
            'login_attempts' => 0,
            'last_attempt' => null,
            'blocked_until' => null,
            'failed_ip' => null,
            'createdon' => now(),
            'createdby' => 'system',
            'updatedon' => null,
            'updatedby' => null,
        ];
    }
}
