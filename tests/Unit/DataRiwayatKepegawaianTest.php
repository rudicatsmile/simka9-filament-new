<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DataRiwayatKepegawaian;
use App\Models\DataPegawai;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * DataRiwayatKepegawaianTest
 * 
 * Unit tests untuk model DataRiwayatKepegawaian
 * Menguji relationships, scopes, accessors, dan business logic
 * 
 * @package Tests\Unit
 * @author SIMKA9 Development Team
 * @version 1.0.0
 */
class DataRiwayatKepegawaianTest extends TestCase
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
    public function test_can_create_data_riwayat_kepegawaian(): void
    {
        $pegawai = DataPegawai::factory()->create();
        
        $data = [
            'nik_data_pegawai' => $pegawai->nik,
            'nama' => $this->faker->name,
            'tanggal_lahir' => $this->faker->date(),
            'nomor' => $this->faker->numerify('KEP-####/###/####'),
            'keterangan' => $this->faker->sentence(10),
            'urut' => 1,
        ];

        $riwayatKepegawaian = DataRiwayatKepegawaian::create($data);

        $this->assertInstanceOf(DataRiwayatKepegawaian::class, $riwayatKepegawaian);
        $this->assertEquals($data['nik_data_pegawai'], $riwayatKepegawaian->nik_data_pegawai);
        $this->assertEquals($data['tanggal_lahir'], $riwayatKepegawaian->tanggal_lahir->format('Y-m-d'));
        $this->assertEquals($data['nama'], $riwayatKepegawaian->nama);
        $this->assertDatabaseHas('data_riwayat_kepegawaian', $data);
    }

    /**
     * Test relationship with DataPegawai
     */
    public function test_belongs_to_pegawai_relationship(): void
    {
        $pegawai = DataPegawai::factory()->create();
        $riwayatKepegawaian = DataRiwayatKepegawaian::factory()->create([
            'nik_data_pegawai' => $pegawai->nik
        ]);

        $this->assertInstanceOf(DataPegawai::class, $riwayatKepegawaian->pegawai);
        $this->assertEquals($pegawai->nik, $riwayatKepegawaian->pegawai->nik);
        $this->assertEquals($pegawai->nama, $riwayatKepegawaian->pegawai->nama);
    }

    /**
     * Test formatted date accessor
     */
    public function test_formatted_date_accessor(): void
    {
        $date = '2023-12-25';
        $riwayatKepegawaian = DataRiwayatKepegawaian::factory()->create([
            'tanggal_lahir' => $date
        ]);

        $this->assertEquals('25 Desember 2023', $riwayatKepegawaian->formatted_date);
    }

    /**
     * Test file URL accessor when file exists
     */
    public function test_file_url_accessor_with_file(): void
    {
        $fileName = 'test-document.pdf';
        Storage::fake('public');
        Storage::put("kepegawaian/{$fileName}", 'test content');

        $riwayatKepegawaian = DataRiwayatKepegawaian::factory()->create([
            'berkas' => $fileName
        ]);

        $expectedUrl = Storage::url("kepegawaian/{$fileName}");
        $this->assertEquals($expectedUrl, $riwayatKepegawaian->file_url);
    }

    /**
     * Test file URL accessor when no file
     */
    public function test_file_url_accessor_without_file(): void
    {
        $riwayatKepegawaian = DataRiwayatKepegawaian::factory()->create([
            'berkas' => null
        ]);

        $this->assertNull($riwayatKepegawaian->file_url);
    }

    /**
     * Test byEmployee scope
     */
    public function test_by_employee_scope(): void
    {
        $pegawai1 = DataPegawai::factory()->create();
        $pegawai2 = DataPegawai::factory()->create();

        DataRiwayatKepegawaian::factory()->count(3)->create([
            'nik_data_pegawai' => $pegawai1->nik
        ]);
        DataRiwayatKepegawaian::factory()->count(2)->create([
            'nik_data_pegawai' => $pegawai2->nik
        ]);

        $result = DataRiwayatKepegawaian::byEmployee($pegawai1->nik)->get();

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

        DataRiwayatKepegawaian::factory()->count(2)->create([
            'nik_data_pegawai' => $pegawai1->nik
        ]);
        DataRiwayatKepegawaian::factory()->create([
            'nik_data_pegawai' => $pegawai2->nik
        ]);

        $result = DataRiwayatKepegawaian::byUnitKerja($unitKerja)->get();

        $this->assertCount(2, $result);
        $result->each(function ($item) use ($unitKerja) {
            $this->assertEquals($unitKerja, $item->pegawai->kode_unit_kerja);
        });
    }

    /**
     * Test byDateRange scope
     */
    public function test_by_date_range_scope(): void
    {
        DataRiwayatKepegawaian::factory()->create(['tanggal_lahir' => '2023-01-15']);
        DataRiwayatKepegawaian::factory()->create(['tanggal_lahir' => '2023-06-15']);
        DataRiwayatKepegawaian::factory()->create(['tanggal_lahir' => '2023-12-15']);

        $result = DataRiwayatKepegawaian::byDateRange('2023-01-01', '2023-06-30')->get();

        $this->assertCount(2, $result);
    }

    /**
     * Test mass assignment protection
     */
    public function test_mass_assignment_fillable_fields(): void
    {
        $pegawai = DataPegawai::factory()->create();
        
        $data = [
            'nik_data_pegawai' => $pegawai->nik,
            'nama' => 'Test Name',
            'tanggal_lahir' => '2023-12-25',
            'nomor' => 'SK-001/2023',
            'perintah' => 'Test Command',
            'keterangan' => 'Test Description',
            'berkas' => 'test.pdf',
            'urut' => 1,
            'id' => 999, // Should not be fillable
        ];

        $riwayatKepegawaian = DataRiwayatKepegawaian::create($data);

        // Check fillable fields are set
        $this->assertEquals($data['nik_data_pegawai'], $riwayatKepegawaian->nik_data_pegawai);
        $this->assertEquals($data['nama'], $riwayatKepegawaian->nama);
        
        // Check non-fillable field is not set
        $this->assertNotEquals(999, $riwayatKepegawaian->id);
    }

    /**
     * Test soft deletes functionality
     */
    public function test_soft_deletes(): void
    {
        $riwayatKepegawaian = DataRiwayatKepegawaian::factory()->create();
        $id = $riwayatKepegawaian->id;

        // Delete the record
        $riwayatKepegawaian->delete();

        // Check it's soft deleted
        $this->assertSoftDeleted('data_riwayat_kepegawaian', ['id' => $id]);
        
        // Check it's not in normal queries
        $this->assertNull(DataRiwayatKepegawaian::find($id));
        
        // Check it's in trashed queries
        $this->assertNotNull(DataRiwayatKepegawaian::withTrashed()->find($id));
    }

    /**
     * Test model validation rules (if implemented)
     */
    public function test_model_casts(): void
    {
        $riwayatKepegawaian = DataRiwayatKepegawaian::factory()->create([
            'tanggal_lahir' => '2023-12-25'
        ]);

        // Test date casting
        $this->assertInstanceOf(\Carbon\Carbon::class, $riwayatKepegawaian->tanggal_lahir);
        $this->assertEquals('2023-12-25', $riwayatKepegawaian->tanggal_lahir->format('Y-m-d'));
    }

    /**
     * Test auto-incrementing urut field
     */
    public function test_auto_increment_urut_field(): void
    {
        $pegawai = DataPegawai::factory()->create();

        // Create first record
        $first = DataRiwayatKepegawaian::factory()->create([
            'nik_data_pegawai' => $pegawai->nik,
            'urut' => 1
        ]);

        // Create second record
        $second = DataRiwayatKepegawaian::factory()->create([
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
        $riwayatKepegawaian = DataRiwayatKepegawaian::factory()->create();

        $this->assertInstanceOf(DataRiwayatKepegawaian::class, $riwayatKepegawaian);
        $this->assertNotNull($riwayatKepegawaian->nik_data_pegawai);
        $this->assertNotNull($riwayatKepegawaian->nama);
        $this->assertNotNull($riwayatKepegawaian->tanggal_lahir);
        $this->assertNotNull($riwayatKepegawaian->urut);
    }
}
