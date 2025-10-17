<?php

namespace Database\Factories;

use App\Models\DataPegawai;
use App\Models\TabelJenisPelatihan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataRiwayatPelatihan>
 */
class DataRiwayatPelatihanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pelatihanOptions = [
            'Pelatihan Kepemimpinan',
            'Pelatihan Manajemen Proyek',
            'Pelatihan Teknologi Informasi',
            'Pelatihan Keuangan dan Akuntansi',
            'Pelatihan Sumber Daya Manusia',
            'Pelatihan Komunikasi Efektif',
            'Pelatihan Pelayanan Prima',
            'Pelatihan Administrasi Perkantoran',
            'Pelatihan Bahasa Inggris',
            'Pelatihan Komputer Dasar',
            'Pelatihan Microsoft Office',
            'Pelatihan Desain Grafis',
            'Pelatihan Digital Marketing',
            'Pelatihan Analisis Data',
            'Pelatihan Keselamatan Kerja',
            'Pelatihan Audit Internal',
            'Pelatihan Pengadaan Barang dan Jasa',
            'Pelatihan Manajemen Kualitas',
            'Pelatihan Inovasi dan Kreativitas',
            'Pelatihan Etika Profesi',
        ];

        $penyelenggaraOptions = [
            'Lembaga Administrasi Negara (LAN)',
            'Badan Pengembangan SDM',
            'Pusat Pendidikan dan Pelatihan',
            'Institut Pemerintahan Dalam Negeri',
            'Universitas Indonesia',
            'Institut Teknologi Bandung',
            'Universitas Gadjah Mada',
            'Balai Diklat Keuangan',
            'Pusat Pelatihan Teknologi',
            'Lembaga Sertifikasi Profesi',
            'Asosiasi Profesi Indonesia',
            'Kamar Dagang dan Industri',
            'Badan Standardisasi Nasional',
            'Kementerian Pendayagunaan Aparatur Negara',
            'Badan Kepegawaian Negara',
        ];

        $angkatanOptions = [
            'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
            '2023-1', '2023-2', '2024-1', '2024-2', '2025-1',
        ];

        $tanggalPelatihan = $this->faker->dateTimeBetween('-5 years', 'now');
        $tanggalSertifikat = $this->faker->optional(0.8)->dateTimeBetween($tanggalPelatihan, 'now');

        return [
            'nik_data_pegawai' => DataPegawai::factory(),
            'nama' => $this->faker->randomElement($pelatihanOptions),
            'kode_tabel_jenis_pelatihan' => TabelJenisPelatihan::factory(),
            'penyelenggara' => $this->faker->randomElement($penyelenggaraOptions),
            'angkatan' => $this->faker->optional(0.7)->randomElement($angkatanOptions),
            'nomor' => $this->faker->optional(0.6)->regexify('[A-Z]{2}[0-9]{4}/[A-Z]{3}/[0-9]{4}'),
            'tanggal' => $tanggalPelatihan,
            'tanggal_sertifikat' => $tanggalSertifikat,
            'berkas' => $this->faker->optional(0.5)->filePath(),
            'urut' => $this->faker->numberBetween(1, 10),
        ];
    }

    /**
     * Create a factory instance with a specific sequence number for urut field.
     *
     * @param int $sequence
     * @return static
     */
    public function withSequence(int $sequence): static
    {
        return $this->state(function (array $attributes) use ($sequence) {
            return [
                'urut' => $sequence,
            ];
        });
    }

    /**
     * Create a factory instance with certificate file.
     *
     * @return static
     */
    public function withCertificate(): static
    {
        return $this->state(function (array $attributes) {
            $extensions = ['pdf', 'jpg', 'png'];
            $extension = $this->faker->randomElement($extensions);
            
            return [
                'berkas' => 'pelatihan-certificates/' . $this->faker->uuid . '.' . $extension,
                'nomor' => $this->faker->regexify('[A-Z]{2}[0-9]{4}/[A-Z]{3}/[0-9]{4}'),
                'tanggal_sertifikat' => $this->faker->dateTimeBetween($attributes['tanggal'] ?? '-1 year', 'now'),
            ];
        });
    }

    /**
     * Create a factory instance without certificate.
     *
     * @return static
     */
    public function withoutCertificate(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'berkas' => null,
                'nomor' => null,
                'tanggal_sertifikat' => null,
            ];
        });
    }
}