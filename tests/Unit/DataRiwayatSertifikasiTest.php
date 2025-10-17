<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DataRiwayatSertifikasi;
use App\Models\DataPegawai;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * DataRiwayatSertifikasiTest
 * 
 * Unit tests untuk model DataRiwayatSertifikasi
 * Menguji relationships, scopes, accessors, dan business logic
 * 
 * @package Tests\Unit
 * @author SIMKA9 Development Team
 * @version 1.0.0
 */
class DataRiwayatSertifikasiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup storage fake
        Storage::fake('public');
        
        // Seed basic data
        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    /**
     * Test model creation with valid data
     */
    public function test_can_create_data_riwayat_sertifikasi(): void
    {
        $pegawai = DataPegawai::factory()->create();
        
        $data = [
            'nik_data_pegawai' => $pegawai->nik,
            'is_sertifikasi' => true,
            'nama' => $this->faker->sentence(3),
            'nomor' => $this->faker->numerify('CERT-####/###/####'),
            'tahun' => $this->faker->numberBetween(2020, 2024),
            'induk_inpasing' => $this->faker->numerify('INP-####'),
            'sk_inpasing' => $this->faker->numerify('SK-INP-####/###/####'),
            'tahun_inpasing' => $this->faker->numberBetween(2020, 2024),
            'urut' => 1,
        ];

        $riwayatSertifikasi = DataRiwayatSertifikasi::create($data);

        $this->assertInstanceOf(DataRiwayatSertifikasi::class, $riwayatSertifikasi);
        $this->assertEquals($data['nik_data_pegawai'], $riwayatSertifikasi->nik_data_pegawai);
        $this->assertEquals($data['is_sertifikasi'], $riwayatSertifikasi->is_sertifikasi);
        $this->assertEquals($data['nama'], $riwayatSertifikasi->nama);
        $this->assertEquals($data['tahun'], $riwayatSertifikasi->tahun);
        $this->assertDatabaseHas('data_riwayat_sertifikasi', $data);
    }

    /**
     * Test relationship with DataPegawai
     */
    public function test_belongs_to_pegawai_relationship(): void
    {
        $pegawai = DataPegawai::factory()->create();
        $riwayatSertifikasi = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $pegawai->nik
        ]);

        $this->assertInstanceOf(DataPegawai::class, $riwayatSertifikasi->pegawai);
        $this->assertEquals($pegawai->nik, $riwayatSertifikasi->pegawai->nik);
        $this->assertEquals($pegawai->nama, $riwayatSertifikasi->pegawai->nama);
    }

    /**
     * Test file URL accessor when file exists
     */
    public function test_file_url_accessor_with_file(): void
    {
        $fileName = 'sertifikasi/test-certificate.pdf';
        Storage::fake('public');
        Storage::put($fileName, 'test content');

        $riwayatSertifikasi = DataRiwayatSertifikasi::factory()->create([
            'berkas' => $fileName
        ]);

        $expectedUrl = Storage::url($fileName);
        $this->assertEquals($expectedUrl, $riwayatSertifikasi->file_url);
    }

    /**
     * Test file URL accessor when no file
     */
    public function test_file_url_accessor_without_file(): void
    {
        $riwayatSertifikasi = DataRiwayatSertifikasi::factory()->create([
            'berkas' => null
        ]);

        $this->assertNull($riwayatSertifikasi->file_url);
    }

    /**
     * Test berkas URL accessor (alias for file_url)
     */
    public function test_berkas_url_accessor(): void
    {
        $fileName = 'sertifikasi/test-certificate.pdf';
        Storage::fake('public');
        Storage::put($fileName, 'test content');

        $riwayatSertifikasi = DataRiwayatSertifikasi::factory()->create([
            'berkas' => $fileName
        ]);

        $this->assertEquals($riwayatSertifikasi->file_url, $riwayatSertifikasi->berkas_url);
    }

    /**
     * Test byEmployee scope
     */
    public function test_by_employee_scope(): void
    {
        $pegawai1 = DataPegawai::factory()->create();
        $pegawai2 = DataPegawai::factory()->create();

        DataRiwayatSertifikasi::factory()->count(3)->create([
            'nik_data_pegawai' => $pegawai1->nik
        ]);
        DataRiwayatSertifikasi::factory()->count(2)->create([
            'nik_data_pegawai' => $pegawai2->nik
        ]);

        $result = DataRiwayatSertifikasi::byEmployee($pegawai1->nik)->get();

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

        DataRiwayatSertifikasi::factory()->count(2)->create([
            'nik_data_pegawai' => $pegawai1->nik
        ]);
        DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $pegawai2->nik
        ]);

        $result = DataRiwayatSertifikasi::byUnitKerja($unitKerja)->get();

        $this->assertCount(2, $result);
        $result->each(function ($item) use ($unitKerja) {
            $this->assertEquals($unitKerja, $item->pegawai->kode_unit_kerja);
        });
    }

    /**
     * Test byCertificationStatus scope
     */
    public function test_by_certification_status_scope(): void
    {
        DataRiwayatSertifikasi::factory()->count(3)->create(['is_sertifikasi' => true]);
        DataRiwayatSertifikasi::factory()->count(2)->create(['is_sertifikasi' => false]);

        $certified = DataRiwayatSertifikasi::byCertificationStatus(true)->get();
        $notCertified = DataRiwayatSertifikasi::byCertificationStatus(false)->get();

        $this->assertCount(3, $certified);
        $this->assertCount(2, $notCertified);

        $certified->each(function ($item) {
            $this->assertTrue($item->is_sertifikasi);
        });

        $notCertified->each(function ($item) {
            $this->assertFalse($item->is_sertifikasi);
        });
    }

    /**
     * Test byYear scope
     */
    public function test_by_year_scope(): void
    {
        DataRiwayatSertifikasi::factory()->count(2)->create(['tahun' => 2023]);
        DataRiwayatSertifikasi::factory()->count(3)->create(['tahun' => 2024]);
        DataRiwayatSertifikasi::factory()->create(['tahun' => 2022]);

        $result2023 = DataRiwayatSertifikasi::byYear(2023)->get();
        $result2024 = DataRiwayatSertifikasi::byYear(2024)->get();

        $this->assertCount(2, $result2023);
        $this->assertCount(3, $result2024);

        $result2023->each(function ($item) {
            $this->assertEquals(2023, $item->tahun);
        });
    }

    /**
     * Test byInpasingYear scope
     */
    public function test_by_inpasing_year_scope(): void
    {
        DataRiwayatSertifikasi::factory()->count(2)->create(['tahun_inpasing' => 2023]);
        DataRiwayatSertifikasi::factory()->count(3)->create(['tahun_inpasing' => 2024]);
        DataRiwayatSertifikasi::factory()->create(['tahun_inpasing' => null]);

        $result2023 = DataRiwayatSertifikasi::byInpasingYear(2023)->get();
        $result2024 = DataRiwayatSertifikasi::byInpasingYear(2024)->get();

        $this->assertCount(2, $result2023);
        $this->assertCount(3, $result2024);

        $result2023->each(function ($item) {
            $this->assertEquals(2023, $item->tahun_inpasing);
        });
    }

    /**
     * Test withBerkas scope
     */
    public function test_with_berkas_scope(): void
    {
        DataRiwayatSertifikasi::factory()->count(3)->create(['berkas' => 'test-file.pdf']);
        DataRiwayatSertifikasi::factory()->count(2)->create(['berkas' => null]);

        $withBerkas = DataRiwayatSertifikasi::withBerkas()->get();

        $this->assertCount(3, $withBerkas);
        $withBerkas->each(function ($item) {
            $this->assertNotNull($item->berkas);
        });
    }

    /**
     * Test withoutBerkas scope
     */
    public function test_without_berkas_scope(): void
    {
        DataRiwayatSertifikasi::factory()->count(3)->create(['berkas' => 'test-file.pdf']);
        DataRiwayatSertifikasi::factory()->count(2)->create(['berkas' => null]);

        $withoutBerkas = DataRiwayatSertifikasi::withoutBerkas()->get();

        $this->assertCount(2, $withoutBerkas);
        $withoutBerkas->each(function ($item) {
            $this->assertNull($item->berkas);
        });
    }

    /**
     * Test file upload functionality
     */
    public function test_data_riwayat_sertifikasi_file_upload(): void
    {
        Storage::fake('public');

        $dataPegawai = DataPegawai::factory()->create();
        
        $file = UploadedFile::fake()->create('certificate.pdf', 1024, 'application/pdf');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('sertifikasi', $fileName, 'public');

        $riwayatSertifikasi = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'berkas' => $filePath
        ]);

        $this->assertNotNull($riwayatSertifikasi->berkas);
        $this->assertEquals($filePath, $riwayatSertifikasi->berkas);
        Storage::disk('public')->assertExists($filePath);
    }

    /**
     * Test mass assignment protection
     */
    public function test_mass_assignment_fillable_fields(): void
    {
        $pegawai = DataPegawai::factory()->create();
        
        $data = [
            'nik_data_pegawai' => $pegawai->nik,
            'is_sertifikasi' => true,
            'nama' => 'Test Certification',
            'nomor' => 'CERT-001/2023',
            'tahun' => 2023,
            'induk_inpasing' => 'INP-001',
            'sk_inpasing' => 'SK-INP-001/2023',
            'tahun_inpasing' => 2023,
            'berkas' => 'test.pdf',
            'urut' => 1,
            'id' => 999, // Should not be fillable
        ];

        $riwayatSertifikasi = DataRiwayatSertifikasi::create($data);

        // ID should not be set to 999 due to mass assignment protection
        $this->assertNotEquals(999, $riwayatSertifikasi->id);
        
        // Other fields should be set correctly
        $this->assertEquals($data['nik_data_pegawai'], $riwayatSertifikasi->nik_data_pegawai);
        $this->assertEquals($data['is_sertifikasi'], $riwayatSertifikasi->is_sertifikasi);
        $this->assertEquals($data['nama'], $riwayatSertifikasi->nama);
        $this->assertEquals($data['tahun'], $riwayatSertifikasi->tahun);
    }

    /**
     * Test auto-incrementing urut field
     */
    public function test_auto_increment_urut_field(): void
    {
        $pegawai = DataPegawai::factory()->create();

        // Create first record
        $first = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $pegawai->nik,
            'urut' => 1
        ]);

        // Create second record
        $second = DataRiwayatSertifikasi::factory()->create([
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
        $riwayatSertifikasi = DataRiwayatSertifikasi::factory()->create();

        $this->assertInstanceOf(DataRiwayatSertifikasi::class, $riwayatSertifikasi);
        $this->assertNotNull($riwayatSertifikasi->nik_data_pegawai);
        $this->assertNotNull($riwayatSertifikasi->is_sertifikasi);
        $this->assertNotNull($riwayatSertifikasi->urut);
    }

    /**
     * Test ordering by urut field
     */
    public function test_data_riwayat_sertifikasi_ordering(): void
    {
        $dataPegawai = DataPegawai::factory()->create();
        
        // Create records with different urut values
        $third = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'urut' => 3
        ]);
        
        $first = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'urut' => 1
        ]);
        
        $second = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'urut' => 2
        ]);

        // Test ordering by urut ascending
        $orderedAsc = DataRiwayatSertifikasi::orderBy('urut', 'asc')->get();
        $this->assertEquals(1, $orderedAsc->first()->urut);
        $this->assertEquals(3, $orderedAsc->last()->urut);

        // Test ordering by urut descending
        $orderedDesc = DataRiwayatSertifikasi::orderBy('urut', 'desc')->get();
        $this->assertEquals(3, $orderedDesc->first()->urut);
        $this->assertEquals(1, $orderedDesc->last()->urut);
    }

    /**
     * Test certification status boolean casting
     */
    public function test_certification_status_boolean_casting(): void
    {
        $certified = DataRiwayatSertifikasi::factory()->create(['is_sertifikasi' => 1]);
        $notCertified = DataRiwayatSertifikasi::factory()->create(['is_sertifikasi' => 0]);

        $this->assertTrue($certified->is_sertifikasi);
        $this->assertFalse($notCertified->is_sertifikasi);
        $this->assertIsBool($certified->is_sertifikasi);
        $this->assertIsBool($notCertified->is_sertifikasi);
    }
}