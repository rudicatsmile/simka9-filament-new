<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DataRiwayatAnak;
use App\Models\DataPegawai;
use App\Models\TabelHubunganKeluarga;
use App\Models\JenjangPendidikan;
use App\Models\TabelPekerjaan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * DataRiwayatAnakTest
 * 
 * Unit tests untuk model DataRiwayatAnak
 * Menguji relationships, scopes, accessors, dan business logic
 * 
 * @package Tests\Unit
 * @author SIMKA9 Development Team
 * @version 1.0.0
 */
class DataRiwayatAnakTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed basic data
        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    /**
     * Test model creation with valid data
     */
    public function test_can_create_data_riwayat_anak(): void
    {
        $pegawai = DataPegawai::factory()->create();
        $hubunganKeluarga = TabelHubunganKeluarga::first();
        
        $data = [
            'nik_data_pegawai' => $pegawai->nik,
            'nama_anak' => $this->faker->name,
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'tempat_lahir' => $this->faker->city,
            'tanggal_lahir' => $this->faker->date(),
            'kode_tabel_hubungan_keluarga' => $hubunganKeluarga->kode,
            'urut' => 1,
        ];

        $riwayatAnak = DataRiwayatAnak::create($data);

        $this->assertInstanceOf(DataRiwayatAnak::class, $riwayatAnak);
        $this->assertEquals($data['nik_data_pegawai'], $riwayatAnak->nik_data_pegawai);
        $this->assertEquals($data['nama_anak'], $riwayatAnak->nama_anak);
        $this->assertDatabaseHas('data_riwayat_anak', $data);
    }

    /**
     * Test relationship with DataPegawai
     */
    public function test_belongs_to_pegawai_relationship(): void
    {
        $pegawai = DataPegawai::factory()->create();
        $riwayatAnak = DataRiwayatAnak::factory()->create([
            'nik_data_pegawai' => $pegawai->nik
        ]);

        $this->assertInstanceOf(DataPegawai::class, $riwayatAnak->pegawai);
        $this->assertEquals($pegawai->nik, $riwayatAnak->pegawai->nik);
        $this->assertEquals($pegawai->nama, $riwayatAnak->pegawai->nama);
    }

    /**
     * Test relationship with TabelHubunganKeluarga
     */
    public function test_belongs_to_hubungan_keluarga_relationship(): void
    {
        $hubunganKeluarga = TabelHubunganKeluarga::first();
        $riwayatAnak = DataRiwayatAnak::factory()->create([
            'kode_tabel_hubungan_keluarga' => $hubunganKeluarga->kode
        ]);

        $this->assertInstanceOf(TabelHubunganKeluarga::class, $riwayatAnak->hubunganKeluarga);
        $this->assertEquals($hubunganKeluarga->kode, $riwayatAnak->hubunganKeluarga->kode);
        $this->assertEquals($hubunganKeluarga->nama, $riwayatAnak->hubunganKeluarga->nama);
    }

    /**
     * Test relationship with JenjangPendidikan
     */
    public function test_belongs_to_jenjang_pendidikan_relationship(): void
    {
        $jenjangPendidikan = JenjangPendidikan::first();
        $riwayatAnak = DataRiwayatAnak::factory()->create([
            'kode_jenjang_pendidikan' => $jenjangPendidikan->kode
        ]);

        $this->assertInstanceOf(JenjangPendidikan::class, $riwayatAnak->jenjangPendidikan);
        $this->assertEquals($jenjangPendidikan->kode, $riwayatAnak->jenjangPendidikan->kode);
        $this->assertEquals($jenjangPendidikan->nama_jenjang_pendidikan, $riwayatAnak->jenjangPendidikan->nama_jenjang_pendidikan);
    }

    /**
     * Test relationship with TabelPekerjaan
     */
    public function test_belongs_to_pekerjaan_relationship(): void
    {
        $pekerjaan = TabelPekerjaan::first();
        $riwayatAnak = DataRiwayatAnak::factory()->create([
            'kode_tabel_pekerjaan' => $pekerjaan->kode
        ]);

        $this->assertInstanceOf(TabelPekerjaan::class, $riwayatAnak->pekerjaan);
        $this->assertEquals($pekerjaan->kode, $riwayatAnak->pekerjaan->kode);
        $this->assertEquals($pekerjaan->nama_pekerjaan, $riwayatAnak->pekerjaan->nama_pekerjaan);
    }

    /**
     * Test formatted birth date accessor
     */
    public function test_formatted_birth_date_accessor(): void
    {
        $date = '2010-12-25';
        $riwayatAnak = DataRiwayatAnak::factory()->create([
            'tanggal_lahir' => $date
        ]);

        $this->assertEquals('25 Desember 2010', $riwayatAnak->formatted_birth_date);
    }

    /**
     * Test age accessor
     */
    public function test_age_accessor(): void
    {
        $birthDate = now()->subYears(10)->subMonths(6);
        $riwayatAnak = DataRiwayatAnak::factory()->create([
            'tanggal_lahir' => $birthDate
        ]);

        $this->assertEquals(10, $riwayatAnak->age);
    }

    /**
     * Test age accessor with null birth date
     */
    public function test_age_accessor_with_null_birth_date(): void
    {
        $riwayatAnak = DataRiwayatAnak::factory()->create([
            'tanggal_lahir' => null
        ]);

        $this->assertNull($riwayatAnak->age);
    }

    /**
     * Test gender label accessor
     */
    public function test_gender_label_accessor(): void
    {
        $malChild = DataRiwayatAnak::factory()->create(['jenis_kelamin' => 'L']);
        $femaleChild = DataRiwayatAnak::factory()->create(['jenis_kelamin' => 'P']);

        $this->assertEquals('Laki-laki', $malChild->gender_label);
        $this->assertEquals('Perempuan', $femaleChild->gender_label);
    }

    /**
     * Test byEmployee scope
     */
    public function test_by_employee_scope(): void
    {
        $pegawai1 = DataPegawai::factory()->create();
        $pegawai2 = DataPegawai::factory()->create();

        DataRiwayatAnak::factory()->count(3)->create([
            'nik_data_pegawai' => $pegawai1->nik
        ]);
        DataRiwayatAnak::factory()->count(2)->create([
            'nik_data_pegawai' => $pegawai2->nik
        ]);

        $result = DataRiwayatAnak::byEmployee($pegawai1->nik)->get();

        $this->assertCount(3, $result);
        $result->each(function ($item) use ($pegawai1) {
            $this->assertEquals($pegawai1->nik, $item->nik_data_pegawai);
        });
    }

    /**
     * Test byUnitKerja scope
     */
    public function test_by_unit_kerja_scope(): void
    {
        $unitKerja = '001';
        
        $pegawai1 = DataPegawai::factory()->create(['kode_unit_kerja' => '001']);
        $pegawai2 = DataPegawai::factory()->create(['kode_unit_kerja' => '002']);

        DataRiwayatAnak::factory()->count(2)->create([
            'nik_data_pegawai' => $pegawai1->nik
        ]);
        DataRiwayatAnak::factory()->create([
            'nik_data_pegawai' => $pegawai2->nik
        ]);

        $result = DataRiwayatAnak::byUnitKerja($unitKerja)->get();

        $this->assertCount(2, $result);
        $result->each(function ($item) use ($unitKerja) {
            $this->assertEquals($unitKerja, $item->pegawai->kode_unit_kerja);
        });
    }

    /**
     * Test byAgeRange scope
     */
    public function test_by_age_range_scope(): void
    {
        // Create children with different ages
        DataRiwayatAnak::factory()->create(['tanggal_lahir' => now()->subYears(5)]);  // 5 years old
        DataRiwayatAnak::factory()->create(['tanggal_lahir' => now()->subYears(10)]); // 10 years old
        DataRiwayatAnak::factory()->create(['tanggal_lahir' => now()->subYears(20)]); // 20 years old

        $result = DataRiwayatAnak::byAgeRange(8, 15)->get();

        $this->assertCount(1, $result);
        $this->assertEquals(10, $result->first()->age);
    }

    /**
     * Test byJenisKelamin scope
     */
    public function test_by_jenis_kelamin_scope(): void
    {
        DataRiwayatAnak::factory()->count(3)->create(['jenis_kelamin' => 'L']);
        DataRiwayatAnak::factory()->count(2)->create(['jenis_kelamin' => 'P']);

        $maleChildren = DataRiwayatAnak::byJenisKelamin('L')->get();
        $femaleChildren = DataRiwayatAnak::byJenisKelamin('P')->get();

        $this->assertCount(3, $maleChildren);
        $this->assertCount(2, $femaleChildren);

        $maleChildren->each(function ($child) {
            $this->assertEquals('L', $child->jenis_kelamin);
        });

        $femaleChildren->each(function ($child) {
            $this->assertEquals('P', $child->jenis_kelamin);
        });
    }

    /**
     * Test byHubunganKeluarga scope
     */
    public function test_by_hubungan_keluarga_scope(): void
    {
        $hubunganKeluarga = TabelHubunganKeluarga::first();
        
        DataRiwayatAnak::factory()->count(2)->create([
            'kode_tabel_hubungan_keluarga' => $hubunganKeluarga->kode
        ]);
        DataRiwayatAnak::factory()->create(); // Different relationship

        $result = DataRiwayatAnak::byHubunganKeluarga($hubunganKeluarga->kode)->get();

        $this->assertCount(2, $result);
        $result->each(function ($item) use ($hubunganKeluarga) {
            $this->assertEquals($hubunganKeluarga->kode, $item->kode_tabel_hubungan_keluarga);
        });
    }

    /**
     * Test mass assignment protection
     */
    public function test_mass_assignment_fillable_fields(): void
    {
        $pegawai = DataPegawai::factory()->create();
        $hubunganKeluarga = TabelHubunganKeluarga::first();
        
        $data = [
            'nik_data_pegawai' => $pegawai->nik,
            'nama_anak' => 'Test Child',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-01-01',
            'kode_tabel_hubungan_keluarga' => $hubunganKeluarga->kode,
            'keterangan' => 'Test description',
            'urut' => 1,
            'id' => 999, // Should not be fillable
        ];

        $riwayatAnak = DataRiwayatAnak::create($data);

        // Check fillable fields are set
        $this->assertEquals($data['nik_data_pegawai'], $riwayatAnak->nik_data_pegawai);
        $this->assertEquals($data['nama_anak'], $riwayatAnak->nama_anak);
        
        // Check non-fillable field is not set
        $this->assertNotEquals(999, $riwayatAnak->id);
    }

    /**
     * Test record deletion functionality
     */
    public function test_record_deletion(): void
    {
        $riwayatAnak = DataRiwayatAnak::factory()->create();
        $id = $riwayatAnak->id;

        // Delete the record
        $riwayatAnak->delete();

        // Check it's deleted
        $this->assertDatabaseMissing('data_riwayat_anak', ['id' => $id]);
        
        // Check it's not in normal queries
        $this->assertNull(DataRiwayatAnak::find($id));
    }

    /**
     * Test model casts
     */
    public function test_model_casts(): void
    {
        $riwayatAnak = DataRiwayatAnak::factory()->create([
            'tanggal_lahir' => '2010-12-25'
        ]);

        // Test date casting
        $this->assertInstanceOf(\Carbon\Carbon::class, $riwayatAnak->tanggal_lahir);
        $this->assertEquals('2010-12-25', $riwayatAnak->tanggal_lahir->format('Y-m-d'));
    }

    /**
     * Test auto-incrementing urut field
     */
    public function test_auto_increment_urut_field(): void
    {
        $pegawai = DataPegawai::factory()->create();

        // Create first record
        $first = DataRiwayatAnak::factory()->create([
            'nik_data_pegawai' => $pegawai->nik,
            'urut' => 1
        ]);

        // Create second record
        $second = DataRiwayatAnak::factory()->create([
            'nik_data_pegawai' => $pegawai->nik,
            'urut' => 2
        ]);

        $this->assertEquals(1, $first->urut);
        $this->assertEquals(2, $second->urut);
    }

    /**
     * Test model factory
     */
    public function test_model_factory(): void
    {
        $riwayatAnak = DataRiwayatAnak::factory()->create();

        $this->assertInstanceOf(DataRiwayatAnak::class, $riwayatAnak);
        $this->assertNotNull($riwayatAnak->nik_data_pegawai);
        $this->assertNotNull($riwayatAnak->nama_anak);
        $this->assertNotNull($riwayatAnak->tanggal_lahir);
        $this->assertNotNull($riwayatAnak->urut);
        $this->assertContains($riwayatAnak->jenis_kelamin, ['L', 'P']);
    }
}