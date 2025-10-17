<?php

namespace Database\Seeders;

use App\Models\DataRiwayatAnak;
use App\Models\DataPegawai;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeder for DataRiwayatAnak model
 *
 * Creates sample data for employee children records with realistic
 * distribution and proper relationships to existing employees.
 *
 * @author Laravel Filament
 * @version 1.0.0
 */
class DataRiwayatAnakSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Create children for existing employees (if any)
        // $existingEmployees = DataPegawai::where('pstatus', '1')->limit(10)->get();

        // foreach ($existingEmployees as $employee) {
        //     $childrenCount = rand(1, 3);

        //     for ($i = 1; $i <= $childrenCount; $i++) {
        //         DataRiwayatAnak::factory()
        //             ->forPegawai($employee->nik)
        //             ->withSequence($i)
        //             ->create();
        //     }
        // }

        // Create additional random children data
        //DataRiwayatAnak::factory(15)->create();

        $pegawais = \App\Models\DataPegawai::limit(15)->get();
        $jenjangOptions = ['004', '005', '006'];
        $pekerjaanOptions = ['006', '007', '008', '009', '010', '011'];
        $hubunganOptions = ['003', '003'];

        foreach ($pegawais as $pegawai) {
            $faker = \Faker\Factory::create('id_ID');
            DataRiwayatAnak::insert([
                'nik_data_pegawai' => $pegawai->nik,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'kode_tabel_hubungan_keluarga' => $faker->randomElement($hubunganOptions),
                'kode_jenjang_pendidikan' => $faker->randomElement($jenjangOptions),
                'kode_tabel_pekerjaan' => $faker->randomElement($pekerjaanOptions),
                'urut' => 1,
                'nama_anak' => $faker->name(),
            ]);
        }

        $this->command->info('DataRiwayatAnak seeder completed successfully!');
        $this->command->info('Total children records created: ' . DataRiwayatAnak::count());
    }
}
