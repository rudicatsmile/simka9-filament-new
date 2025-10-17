<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DataRiwayatPasangan;
use App\Models\DataPegawai;
use App\Models\JenjangPendidikan;
use App\Models\TabelPekerjaan;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Unit tests for DataRiwayatPasangan model
 * 
 * @package Tests\Unit
 * @author Laravel Test Suite
 * @version 1.0.0
 */
class DataRiwayatPasanganTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test DataRiwayatPasangan model can be created
     * 
     * @return void
     */
    public function test_data_riwayat_pasangan_can_be_created(): void
    {
        $dataPegawai = DataPegawai::factory()->create();
        $jenjangPendidikan = JenjangPendidikan::factory()->create();
        $tabelPekerjaan = TabelPekerjaan::factory()->create();

        $dataRiwayatPasangan = DataRiwayatPasangan::create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'nama_pasangan' => 'Siti Nurhaliza',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1985-05-15',
            'hubungan' => 'Istri',
            'kode_jenjang_pendidikan' => $jenjangPendidikan->kode,
            'kode_tabel_pekerjaan' => $tabelPekerjaan->kode,
            'urut' => 1
        ]);

        $this->assertInstanceOf(DataRiwayatPasangan::class, $dataRiwayatPasangan);
        $this->assertEquals($dataPegawai->nik, $dataRiwayatPasangan->nik_data_pegawai);
        $this->assertEquals('Siti Nurhaliza', $dataRiwayatPasangan->nama_pasangan);
        $this->assertEquals('Jakarta', $dataRiwayatPasangan->tempat_lahir);
        $this->assertEquals('1985-05-15', $dataRiwayatPasangan->tanggal_lahir->format('Y-m-d'));
        $this->assertEquals('Istri', $dataRiwayatPasangan->hubungan);
        $this->assertEquals($jenjangPendidikan->kode, $dataRiwayatPasangan->kode_jenjang_pendidikan);
        $this->assertEquals($tabelPekerjaan->kode, $dataRiwayatPasangan->kode_tabel_pekerjaan);
        $this->assertEquals(1, $dataRiwayatPasangan->urut);
    }

    /**
     * Test DataRiwayatPasangan fillable attributes
     * 
     * @return void
     */
    public function test_data_riwayat_pasangan_fillable_attributes(): void
    {
        $dataRiwayatPasangan = new DataRiwayatPasangan();
        $fillable = $dataRiwayatPasangan->getFillable();

        $expectedFillable = [
            'nik_data_pegawai',
            'nama_pasangan',
            'tempat_lahir',
            'tanggal_lahir',
            'hubungan',
            'kode_jenjang_pendidikan',
            'kode_tabel_pekerjaan',
            'urut'
        ];

        $this->assertEquals($expectedFillable, $fillable);
    }

    /**
     * Test DataRiwayatPasangan casts
     * 
     * @return void
     */
    public function test_data_riwayat_pasangan_casts(): void
    {
        $dataRiwayatPasangan = new DataRiwayatPasangan();
        $casts = $dataRiwayatPasangan->getCasts();

        $this->assertEquals('date', $casts['tanggal_lahir']);
        $this->assertEquals('integer', $casts['urut']);
    }

    /**
     * Test DataRiwayatPasangan table name
     * 
     * @return void
     */
    public function test_data_riwayat_pasangan_table_name(): void
    {
        $dataRiwayatPasangan = new DataRiwayatPasangan();
        $this->assertEquals('data_riwayat_pasangan', $dataRiwayatPasangan->getTable());
    }

    /**
     * Test DataRiwayatPasangan belongs to DataPegawai relationship
     * 
     * @return void
     */
    public function test_data_riwayat_pasangan_belongs_to_data_pegawai(): void
    {
        $dataPegawai = DataPegawai::factory()->create();
        $dataRiwayatPasangan = DataRiwayatPasangan::factory()->create([
            'nik_data_pegawai' => $dataPegawai->nik
        ]);

        $this->assertInstanceOf(DataPegawai::class, $dataRiwayatPasangan->dataPegawai);
        $this->assertEquals($dataPegawai->nik, $dataRiwayatPasangan->dataPegawai->nik);
    }

    /**
     * Test DataRiwayatPasangan belongs to JenjangPendidikan relationship
     * 
     * @return void
     */
    public function test_data_riwayat_pasangan_belongs_to_jenjang_pendidikan(): void
    {
        $jenjangPendidikan = JenjangPendidikan::factory()->create();
        $dataRiwayatPasangan = DataRiwayatPasangan::factory()->create([
            'kode_jenjang_pendidikan' => $jenjangPendidikan->kode
        ]);

        $this->assertInstanceOf(JenjangPendidikan::class, $dataRiwayatPasangan->jenjangPendidikan);
        $this->assertEquals($jenjangPendidikan->kode, $dataRiwayatPasangan->jenjangPendidikan->kode);
    }

    /**
     * Test DataRiwayatPasangan belongs to TabelPekerjaan relationship
     * 
     * @return void
     */
    public function test_data_riwayat_pasangan_belongs_to_tabel_pekerjaan(): void
    {
        $tabelPekerjaan = TabelPekerjaan::factory()->create();
        $dataRiwayatPasangan = DataRiwayatPasangan::factory()->create([
            'kode_tabel_pekerjaan' => $tabelPekerjaan->kode
        ]);

        $this->assertInstanceOf(TabelPekerjaan::class, $dataRiwayatPasangan->tabelPekerjaan);
        $this->assertEquals($tabelPekerjaan->kode, $dataRiwayatPasangan->tabelPekerjaan->kode);
    }

    /**
     * Test DataRiwayatPasangan factory
     * 
     * @return void
     */
    public function test_data_riwayat_pasangan_factory(): void
    {
        $dataRiwayatPasangan = DataRiwayatPasangan::factory()->create();

        $this->assertInstanceOf(DataRiwayatPasangan::class, $dataRiwayatPasangan);
        $this->assertNotEmpty($dataRiwayatPasangan->nik_data_pegawai);
        $this->assertNotEmpty($dataRiwayatPasangan->nama_pasangan);
        $this->assertNotEmpty($dataRiwayatPasangan->tempat_lahir);
        $this->assertNotNull($dataRiwayatPasangan->tanggal_lahir);
        $this->assertContains($dataRiwayatPasangan->hubungan, ['Suami', 'Istri']);
        $this->assertIsInt($dataRiwayatPasangan->urut);
    }

    /**
     * Test DataRiwayatPasangan scopes
     * 
     * @return void
     */
    public function test_data_riwayat_pasangan_scopes(): void
    {
        $dataPegawai = DataPegawai::factory()->create();
        $jenjangPendidikan = JenjangPendidikan::factory()->create();
        $tabelPekerjaan = TabelPekerjaan::factory()->create();
        
        DataRiwayatPasangan::factory()->create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'hubungan' => 'Istri',
            'kode_jenjang_pendidikan' => $jenjangPendidikan->kode,
            'kode_tabel_pekerjaan' => $tabelPekerjaan->kode,
            'tanggal_lahir' => '1985-05-15'
        ]);

        // Test byEmployee scope
        $result = DataRiwayatPasangan::byEmployee($dataPegawai->nik)->get();
        $this->assertCount(1, $result);

        // Test byRelation scope
        $result = DataRiwayatPasangan::byRelation('Istri')->get();
        $this->assertCount(1, $result);

        // Test byEducationLevel scope
        $result = DataRiwayatPasangan::byEducationLevel($jenjangPendidikan->kode)->get();
        $this->assertCount(1, $result);

        // Test byJob scope
        $result = DataRiwayatPasangan::byJob($tabelPekerjaan->kode)->get();
        $this->assertCount(1, $result);

        // Test ordered scope
        $result = DataRiwayatPasangan::ordered()->get();
        $this->assertCount(1, $result);
    }

    /**
     * Test DataRiwayatPasangan accessors
     * 
     * @return void
     */
    public function test_data_riwayat_pasangan_accessors(): void
    {
        $dataRiwayatPasangan = DataRiwayatPasangan::factory()->create([
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1985-05-15'
        ]);

        // Test birth_info accessor
        $birthInfo = $dataRiwayatPasangan->birth_info;
        $this->assertStringContainsString('Jakarta', $birthInfo);
        $this->assertStringContainsString('15/05/1985', $birthInfo);

        // Test age accessor
        $age = $dataRiwayatPasangan->age;
        $this->assertIsInt($age);
        $this->assertGreaterThan(0, $age);
    }
}