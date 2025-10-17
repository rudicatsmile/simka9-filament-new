<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\RiwayatPendidikan;
use App\Models\DataPegawai;
use App\Models\JenjangPendidikan;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Unit tests for RiwayatPendidikan model
 * 
 * @package Tests\Unit
 * @author Laravel Test Suite
 * @version 1.0.0
 */
class RiwayatPendidikanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test RiwayatPendidikan model can be created
     * 
     * @return void
     */
    public function test_riwayat_pendidikan_can_be_created(): void
    {
        $dataPegawai = DataPegawai::factory()->create();
        $jenjangPendidikan = JenjangPendidikan::factory()->create();

        $riwayatPendidikan = RiwayatPendidikan::create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'kode_jenjang_pendidikan' => $jenjangPendidikan->kode,
            'nama_sekolah' => 'Universitas Indonesia',
            'tahun_ijazah' => 2020,
            'urut' => 1
        ]);

        $this->assertInstanceOf(RiwayatPendidikan::class, $riwayatPendidikan);
        $this->assertEquals($dataPegawai->nik, $riwayatPendidikan->nik_data_pegawai);
        $this->assertEquals($jenjangPendidikan->kode, $riwayatPendidikan->kode_jenjang_pendidikan);
        $this->assertEquals('Universitas Indonesia', $riwayatPendidikan->nama_sekolah);
        $this->assertEquals(2020, $riwayatPendidikan->tahun_ijazah);
        $this->assertEquals(1, $riwayatPendidikan->urut);
    }

    /**
     * Test RiwayatPendidikan fillable attributes
     * 
     * @return void
     */
    public function test_riwayat_pendidikan_fillable_attributes(): void
    {
        $riwayatPendidikan = new RiwayatPendidikan();
        $fillable = $riwayatPendidikan->getFillable();

        $expectedFillable = [
            'nik_data_pegawai',
            'kode_jenjang_pendidikan',
            'nama_sekolah',
            'tahun_ijazah',
            'urut'
        ];

        $this->assertEquals($expectedFillable, $fillable);
    }

    /**
     * Test RiwayatPendidikan casts
     * 
     * @return void
     */
    public function test_riwayat_pendidikan_casts(): void
    {
        $riwayatPendidikan = new RiwayatPendidikan();
        $casts = $riwayatPendidikan->getCasts();

        $this->assertEquals('integer', $casts['tahun_ijazah']);
        $this->assertEquals('integer', $casts['urut']);
    }

    /**
     * Test RiwayatPendidikan table name
     * 
     * @return void
     */
    public function test_riwayat_pendidikan_table_name(): void
    {
        $riwayatPendidikan = new RiwayatPendidikan();
        $this->assertEquals('data_riwayat_pendidikan', $riwayatPendidikan->getTable());
    }

    /**
     * Test RiwayatPendidikan belongs to DataPegawai relationship
     * 
     * @return void
     */
    public function test_riwayat_pendidikan_belongs_to_data_pegawai(): void
    {
        $dataPegawai = DataPegawai::factory()->create();
        $riwayatPendidikan = RiwayatPendidikan::factory()->create([
            'nik_data_pegawai' => $dataPegawai->nik
        ]);

        $this->assertInstanceOf(DataPegawai::class, $riwayatPendidikan->dataPegawai);
        $this->assertEquals($dataPegawai->nik, $riwayatPendidikan->dataPegawai->nik);
    }

    /**
     * Test RiwayatPendidikan belongs to JenjangPendidikan relationship
     * 
     * @return void
     */
    public function test_riwayat_pendidikan_belongs_to_jenjang_pendidikan(): void
    {
        $jenjangPendidikan = JenjangPendidikan::factory()->create();
        $riwayatPendidikan = RiwayatPendidikan::factory()->create([
            'kode_jenjang_pendidikan' => $jenjangPendidikan->kode
        ]);

        $this->assertInstanceOf(JenjangPendidikan::class, $riwayatPendidikan->jenjangPendidikan);
        $this->assertEquals($jenjangPendidikan->kode, $riwayatPendidikan->jenjangPendidikan->kode);
    }

    /**
     * Test RiwayatPendidikan factory
     * 
     * @return void
     */
    public function test_riwayat_pendidikan_factory(): void
    {
        $riwayatPendidikan = RiwayatPendidikan::factory()->create();

        $this->assertInstanceOf(RiwayatPendidikan::class, $riwayatPendidikan);
        $this->assertNotEmpty($riwayatPendidikan->nik_data_pegawai);
        $this->assertNotEmpty($riwayatPendidikan->kode_jenjang_pendidikan);
        $this->assertNotEmpty($riwayatPendidikan->nama_sekolah);
        $this->assertIsInt($riwayatPendidikan->tahun_ijazah);
        $this->assertIsInt($riwayatPendidikan->urut);
    }

    /**
     * Test RiwayatPendidikan scopes
     * 
     * @return void
     */
    public function test_riwayat_pendidikan_scopes(): void
    {
        $dataPegawai = DataPegawai::factory()->create();
        $jenjangPendidikan = JenjangPendidikan::factory()->create();
        
        RiwayatPendidikan::factory()->create([
            'nik_data_pegawai' => $dataPegawai->nik,
            'kode_jenjang_pendidikan' => $jenjangPendidikan->kode,
            'tahun_ijazah' => 2020
        ]);

        // Test byEmployee scope
        $result = RiwayatPendidikan::byEmployee($dataPegawai->nik)->get();
        $this->assertCount(1, $result);

        // Test byEducationLevel scope
        $result = RiwayatPendidikan::byEducationLevel($jenjangPendidikan->kode)->get();
        $this->assertCount(1, $result);

        // Test byYear scope
        $result = RiwayatPendidikan::byYear(2020)->get();
        $this->assertCount(1, $result);

        // Test ordered scope
        $result = RiwayatPendidikan::ordered()->get();
        $this->assertCount(1, $result);
    }
}