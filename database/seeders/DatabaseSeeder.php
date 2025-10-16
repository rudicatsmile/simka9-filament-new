<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        //CALL BOOK SEEDER
        // $this->call(BookSeeder::class);
        //call AgamaSeeder
        $this->call(AgamaSeeder::class);
        $this->call(UnitKerjaSeeder::class);
        $this->call(JabatanUtamaSeeder::class);
        $this->call(JenjangPendidikanSeeder::class);
        $this->call(TabelGolonganDarahSeeder::class);
        $this->call(TabelHubunganKeluargaSeeder::class);
        $this->call(TabelJenisPelatihanSeeder::class);
        $this->call(TabelPekerjaanSeeder::class);
        $this->call(TabelPropinsiSeeder::class);
        $this->call(TabelStatusKepegawaianSeeder::class);
        $this->call(TabelStatusNikahSeeder::class);
        $this->call(DataPegawaiSeeder::class);
        // Users seeder
        $this->call(UserSeeder::class);
    }
}
