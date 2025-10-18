<?php

namespace Tests\Unit\Factories;

use App\Models\DataPegawai;
use App\Models\DataRiwayatUnitKerjaLain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataRiwayatUnitKerjaLainFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_data_riwayat_unit_kerja_lain_using_factory()
    {
        $riwayat = DataRiwayatUnitKerjaLain::factory()->create();

        $this->assertInstanceOf(DataRiwayatUnitKerjaLain::class, $riwayat);
        $this->assertDatabaseHas('data_riwayat_unit_kerja_lain', [
            'id' => $riwayat->id,
        ]);
    }

    /** @test */
    public function it_creates_valid_data_structure()
    {
        $riwayat = DataRiwayatUnitKerjaLain::factory()->create();

        $this->assertNotNull($riwayat->nik_data_pegawai);
        $this->assertNotNull($riwayat->nama_unit_kerja);
        $this->assertNotNull($riwayat->alamat_unit_kerja);
        $this->assertNotNull($riwayat->jabatan);
        $this->assertNotNull($riwayat->tanggal_mulai);
        $this->assertNotNull($riwayat->status);
        $this->assertIsBool($riwayat->is_bekerja_di_tempat_lain);
    }

    /** @test */
    public function it_can_create_multiple_records()
    {
        $count = 5;
        $riwayats = DataRiwayatUnitKerjaLain::factory()->count($count)->create();

        $this->assertCount($count, $riwayats);
        $this->assertEquals($count, DataRiwayatUnitKerjaLain::count());
    }

    /** @test */
    public function it_can_override_factory_attributes()
    {
        $customData = [
            'nama_unit_kerja' => 'Custom Unit Kerja',
            'jabatan' => 'Custom Jabatan',
            'status' => 'selesai',
        ];

        $riwayat = DataRiwayatUnitKerjaLain::factory()->create($customData);

        $this->assertEquals($customData['nama_unit_kerja'], $riwayat->nama_unit_kerja);
        $this->assertEquals($customData['jabatan'], $riwayat->jabatan);
        $this->assertEquals($customData['status'], $riwayat->status);
    }

    /** @test */
    public function it_creates_valid_status_values()
    {
        $riwayats = DataRiwayatUnitKerjaLain::factory()->count(10)->create();
        $validStatuses = ['aktif', 'tidak_aktif', 'selesai'];

        foreach ($riwayats as $riwayat) {
            $this->assertContains($riwayat->status, $validStatuses);
        }
    }

    /** @test */
    public function it_creates_valid_date_ranges()
    {
        $riwayat = DataRiwayatUnitKerjaLain::factory()->create();

        $this->assertInstanceOf(\Carbon\Carbon::class, $riwayat->tanggal_mulai);
        
        if ($riwayat->tanggal_selesai) {
            $this->assertInstanceOf(\Carbon\Carbon::class, $riwayat->tanggal_selesai);
            $this->assertTrue($riwayat->tanggal_selesai->gte($riwayat->tanggal_mulai));
        }
    }

    /** @test */
    public function it_can_create_with_specific_pegawai()
    {
        $pegawai = DataPegawai::factory()->create();
        
        $riwayat = DataRiwayatUnitKerjaLain::factory()->create([
            'nik_data_pegawai' => $pegawai->nik,
        ]);

        $this->assertEquals($pegawai->nik, $riwayat->nik_data_pegawai);
        $this->assertEquals($pegawai->id, $riwayat->dataPegawai->id);
    }

    /** @test */
    public function it_creates_realistic_unit_kerja_names()
    {
        $riwayat = DataRiwayatUnitKerjaLain::factory()->create();

        $this->assertIsString($riwayat->nama_unit_kerja);
        $this->assertGreaterThan(5, strlen($riwayat->nama_unit_kerja));
    }

    /** @test */
    public function it_creates_realistic_addresses()
    {
        $riwayat = DataRiwayatUnitKerjaLain::factory()->create();

        $this->assertIsString($riwayat->alamat_unit_kerja);
        $this->assertGreaterThan(10, strlen($riwayat->alamat_unit_kerja));
    }

    /** @test */
    public function it_creates_realistic_job_positions()
    {
        $riwayat = DataRiwayatUnitKerjaLain::factory()->create();

        $this->assertIsString($riwayat->jabatan);
        $this->assertGreaterThan(3, strlen($riwayat->jabatan));
    }
}