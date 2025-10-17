<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DataRiwayatPelatihan;
use App\Models\DataPegawai;
use App\Models\TabelJenisPelatihan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Unit tests for DataRiwayatPelatihan model
 * 
 * @package Tests\Unit
 * @author Laravel Test Suite
 * @version 1.0.0
 */
class DataRiwayatPelatihanTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test DataRiwayatPelatihan model can be created
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_can_be_created(): void
    {
        $dataPegawai = DataPegawai::factory()->create();
        $jenisPelatihan = TabelJenisPelatihan::factory()->create();

        $riwayatPelatihan = DataRiwayatPelatihan::create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'nama' => 'Pelatihan Leadership',
            'kode_tabel_jenis_pelatihan' => $jenisPelatihan->kode,
            'penyelenggara' => 'PT. Training Indonesia',
            'angkatan' => 'Angkatan 1',
            'nomor' => 'CERT-001',
            'tanggal' => '2023-01-15',
            'tanggal_sertifikat' => '2023-01-20',
            'urut' => 1
        ]);

        $this->assertInstanceOf(DataRiwayatPelatihan::class, $riwayatPelatihan);
        $this->assertEquals($dataPegawai->nik, $riwayatPelatihan->nik_data_pegawai);
        $this->assertEquals('Pelatihan Leadership', $riwayatPelatihan->nama);
        $this->assertEquals($jenisPelatihan->kode, $riwayatPelatihan->kode_tabel_jenis_pelatihan);
        $this->assertEquals('PT. Training Indonesia', $riwayatPelatihan->penyelenggara);
        $this->assertEquals('Angkatan 1', $riwayatPelatihan->angkatan);
        $this->assertEquals('CERT-001', $riwayatPelatihan->nomor);
        $this->assertEquals('2023-01-15', $riwayatPelatihan->tanggal->format('Y-m-d'));
        $this->assertEquals('2023-01-20', $riwayatPelatihan->tanggal_sertifikat->format('Y-m-d'));
        $this->assertEquals(1, $riwayatPelatihan->urut);
    }

    /**
     * Test DataRiwayatPelatihan fillable attributes
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_fillable_attributes(): void
    {
        $riwayatPelatihan = new DataRiwayatPelatihan();
        $fillable = $riwayatPelatihan->getFillable();

        $expectedFillable = [
            'nik_data_pegawai',
            'nama',
            'kode_tabel_jenis_pelatihan',
            'penyelenggara',
            'angkatan',
            'nomor',
            'tanggal',
            'tanggal_sertifikat',
            'berkas',
            'urut'
        ];

        $this->assertEquals($expectedFillable, $fillable);
    }

    /**
     * Test DataRiwayatPelatihan casts
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_casts(): void
    {
        $riwayatPelatihan = new DataRiwayatPelatihan();
        $casts = $riwayatPelatihan->getCasts();

        $this->assertEquals('date', $casts['tanggal']);
        $this->assertEquals('date', $casts['tanggal_sertifikat']);
        $this->assertEquals('integer', $casts['urut']);
    }

    /**
     * Test DataRiwayatPelatihan table name
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_table_name(): void
    {
        $riwayatPelatihan = new DataRiwayatPelatihan();
        $this->assertEquals('data_riwayat_pelatihan', $riwayatPelatihan->getTable());
    }

    /**
     * Test DataRiwayatPelatihan belongs to DataPegawai relationship
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_belongs_to_data_pegawai(): void
    {
        $dataPegawai = DataPegawai::factory()->create();
        $riwayatPelatihan = DataRiwayatPelatihan::factory()->create([
            'nik_data_pegawai' => $dataPegawai->nik
        ]);

        $this->assertInstanceOf(DataPegawai::class, $riwayatPelatihan->pegawai);
        $this->assertEquals($dataPegawai->nik, $riwayatPelatihan->pegawai->nik);
    }

    /**
     * Test DataRiwayatPelatihan belongs to TabelJenisPelatihan relationship
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_belongs_to_jenis_pelatihan(): void
    {
        $jenisPelatihan = TabelJenisPelatihan::factory()->create();
        $riwayatPelatihan = DataRiwayatPelatihan::factory()->create([
            'kode_tabel_jenis_pelatihan' => $jenisPelatihan->kode
        ]);

        $this->assertInstanceOf(TabelJenisPelatihan::class, $riwayatPelatihan->jenisPelatihan);
        $this->assertEquals($jenisPelatihan->kode, $riwayatPelatihan->jenisPelatihan->kode);
    }

    /**
     * Test DataRiwayatPelatihan factory
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_factory(): void
    {
        $riwayatPelatihan = DataRiwayatPelatihan::factory()->create();

        $this->assertInstanceOf(DataRiwayatPelatihan::class, $riwayatPelatihan);
        $this->assertNotEmpty($riwayatPelatihan->nik_data_pegawai);
        $this->assertNotEmpty($riwayatPelatihan->nama);
        $this->assertNotEmpty($riwayatPelatihan->kode_tabel_jenis_pelatihan);
        $this->assertNotEmpty($riwayatPelatihan->penyelenggara);
        $this->assertNotNull($riwayatPelatihan->tanggal);
        $this->assertIsInt($riwayatPelatihan->urut);
    }

    /**
     * Test DataRiwayatPelatihan factory with certificate
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_factory_with_certificate(): void
    {
        $riwayatPelatihan = DataRiwayatPelatihan::factory()->withCertificate()->create();

        $this->assertInstanceOf(DataRiwayatPelatihan::class, $riwayatPelatihan);
        $this->assertNotNull($riwayatPelatihan->nomor);
        $this->assertNotNull($riwayatPelatihan->tanggal_sertifikat);
        $this->assertNotNull($riwayatPelatihan->berkas);
    }

    /**
     * Test DataRiwayatPelatihan factory without certificate
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_factory_without_certificate(): void
    {
        $riwayatPelatihan = DataRiwayatPelatihan::factory()->withoutCertificate()->create();

        $this->assertInstanceOf(DataRiwayatPelatihan::class, $riwayatPelatihan);
        $this->assertNull($riwayatPelatihan->nomor);
        $this->assertNull($riwayatPelatihan->tanggal_sertifikat);
        $this->assertNull($riwayatPelatihan->berkas);
    }

    /**
     * Test DataRiwayatPelatihan scopes
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_scopes(): void
    {
        $dataPegawai = DataPegawai::factory()->create();
        $jenisPelatihan = TabelJenisPelatihan::factory()->create();
        
        DataRiwayatPelatihan::factory()->create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'kode_tabel_jenis_pelatihan' => $jenisPelatihan->kode,
            'tanggal' => '2023-01-15'
        ]);

        // Test byEmployee scope
        $result = DataRiwayatPelatihan::byEmployee($dataPegawai->nik)->get();
        $this->assertCount(1, $result);

        // Test byJenisPelatihan scope
        $result = DataRiwayatPelatihan::byJenisPelatihan($jenisPelatihan->kode)->get();
        $this->assertCount(1, $result);

        // Test byYear scope
        $result = DataRiwayatPelatihan::byYear(2023)->get();
        $this->assertCount(1, $result);

        // Test ordered scope
        $result = DataRiwayatPelatihan::ordered()->get();
        $this->assertCount(1, $result);
    }

    /**
     * Test DataRiwayatPelatihan validation rules
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_validation_rules(): void
    {
        $dataPegawai = DataPegawai::factory()->create();
        $jenisPelatihan = TabelJenisPelatihan::factory()->create();

        // Test required fields
        $this->expectException(\Illuminate\Database\QueryException::class);
        DataRiwayatPelatihan::create([]);

        // Test valid creation
        $riwayatPelatihan = DataRiwayatPelatihan::create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'nama' => 'Test Pelatihan',
            'kode_tabel_jenis_pelatihan' => $jenisPelatihan->kode,
            'penyelenggara' => 'Test Penyelenggara',
            'tanggal' => '2023-01-15',
            'urut' => 1
        ]);

        $this->assertInstanceOf(DataRiwayatPelatihan::class, $riwayatPelatihan);
    }

    /**
     * Test DataRiwayatPelatihan file upload functionality
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_file_upload(): void
    {
        Storage::fake('public');

        $dataPegawai = DataPegawai::factory()->create();
        $jenisPelatihan = TabelJenisPelatihan::factory()->create();

        $file = UploadedFile::fake()->create('certificate.pdf', 1024, 'application/pdf');

        $riwayatPelatihan = DataRiwayatPelatihan::create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'nama' => 'Test Pelatihan',
            'kode_tabel_jenis_pelatihan' => $jenisPelatihan->kode,
            'penyelenggara' => 'Test Penyelenggara',
            'tanggal' => '2023-01-15',
            'berkas' => 'pelatihan-certificates/certificate.pdf',
            'urut' => 1
        ]);

        $this->assertEquals('pelatihan-certificates/certificate.pdf', $riwayatPelatihan->berkas);
    }

    /**
     * Test DataRiwayatPelatihan accessors
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_accessors(): void
    {
        $riwayatPelatihan = DataRiwayatPelatihan::factory()->create([
            'berkas' => 'pelatihan-certificates/test.pdf'
        ]);

        // Test berkas URL accessor if exists
        $this->assertNotNull($riwayatPelatihan->berkas);
    }

    /**
     * Test DataRiwayatPelatihan search functionality
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_search(): void
    {
        $dataPegawai = DataPegawai::factory()->create(['nama_lengkap' => 'John Doe']);
        $jenisPelatihan = TabelJenisPelatihan::factory()->create(['nama_jenis_pelatihan' => 'Leadership Training']);
        
        DataRiwayatPelatihan::factory()->create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'nama' => 'Advanced Leadership',
            'kode_tabel_jenis_pelatihan' => $jenisPelatihan->kode,
            'penyelenggara' => 'Training Center'
        ]);

        // Test search by training name
        $result = DataRiwayatPelatihan::where('nama', 'like', '%Leadership%')->get();
        $this->assertCount(1, $result);

        // Test search by organizer
        $result = DataRiwayatPelatihan::where('penyelenggara', 'like', '%Training%')->get();
        $this->assertCount(1, $result);

        // Test search by jenis pelatihan
        $result = DataRiwayatPelatihan::whereHas('jenisPelatihan', function ($subQ) {
            $subQ->where('nama_jenis_pelatihan', 'like', '%Leadership%');
        })->get();
        $this->assertCount(1, $result);
    }

    /**
     * Test DataRiwayatPelatihan date filtering
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_date_filtering(): void
    {
        DataRiwayatPelatihan::factory()->create(['tanggal' => '2023-01-15']);
        DataRiwayatPelatihan::factory()->create(['tanggal' => '2023-06-15']);
        DataRiwayatPelatihan::factory()->create(['tanggal' => '2024-01-15']);

        // Test date range filtering
        $result = DataRiwayatPelatihan::whereBetween('tanggal', ['2023-01-01', '2023-12-31'])->get();
        $this->assertCount(2, $result);

        // Test specific date filtering
        $result = DataRiwayatPelatihan::whereDate('tanggal', '2023-01-15')->get();
        $this->assertCount(1, $result);
    }

    /**
     * Test DataRiwayatPelatihan ordering
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_ordering(): void
    {
        $dataPegawai = DataPegawai::factory()->create();
        
        DataRiwayatPelatihan::factory()->create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'urut' => 3,
            'tanggal' => '2023-03-15'
        ]);
        
        DataRiwayatPelatihan::factory()->create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'urut' => 1,
            'tanggal' => '2023-01-15'
        ]);
        
        DataRiwayatPelatihan::factory()->create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'urut' => 2,
            'tanggal' => '2023-02-15'
        ]);

        // Test ordering by urut
        $result = DataRiwayatPelatihan::orderBy('urut')->get();
        $this->assertEquals(1, $result->first()->urut);
        $this->assertEquals(3, $result->last()->urut);

        // Test ordering by date
        $result = DataRiwayatPelatihan::orderBy('tanggal', 'desc')->get();
        $this->assertEquals('2023-03-15', $result->first()->tanggal->format('Y-m-d'));
        $this->assertEquals('2023-01-15', $result->last()->tanggal->format('Y-m-d'));
    }

    /**
     * Test DataRiwayatPelatihan model events
     * 
     * @return void
     */
    public function test_data_riwayat_pelatihan_model_events(): void
    {
        $dataPegawai = DataPegawai::factory()->create();
        $jenisPelatihan = TabelJenisPelatihan::factory()->create();

        $riwayatPelatihan = DataRiwayatPelatihan::create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'nama' => 'Test Pelatihan',
            'kode_tabel_jenis_pelatihan' => $jenisPelatihan->kode,
            'penyelenggara' => 'Test Penyelenggara',
            'tanggal' => '2023-01-15',
            'urut' => 1
        ]);

        // Test that timestamps are set
        $this->assertNotNull($riwayatPelatihan->created_at);
        $this->assertNotNull($riwayatPelatihan->updated_at);

        // Test update
        $riwayatPelatihan->update(['nama' => 'Updated Pelatihan']);
        $this->assertEquals('Updated Pelatihan', $riwayatPelatihan->nama);
        $this->assertNotNull($riwayatPelatihan->updated_at);
    }
}