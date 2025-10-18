<?php

namespace Tests\Unit\Models;

use App\Models\DataPegawai;
use App\Models\DataRiwayatUnitKerjaLain;
use App\Models\UnitKerja;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataRiwayatUnitKerjaLainTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->unitKerja = UnitKerja::factory()->create();
        $this->pegawai = DataPegawai::factory()->create();
    }

    /** @test */
    public function it_can_create_data_riwayat_unit_kerja_lain()
    {
        $data = [
            'nik_data_pegawai' => $this->pegawai->nik,
            'nama_unit_kerja' => 'Unit Kerja Test',
            'alamat_unit_kerja' => 'Jl. Test No. 123',
            'jabatan' => 'Staff Test',
            'tanggal_mulai' => '2023-01-01',
            'tanggal_selesai' => '2023-12-31',
            'is_bekerja_di_tempat_lain' => true,
            'status' => 'aktif',
        ];

        $riwayat = DataRiwayatUnitKerjaLain::create($data);

        $this->assertInstanceOf(DataRiwayatUnitKerjaLain::class, $riwayat);
        $this->assertEquals($data['nik_data_pegawai'], $riwayat->nik_data_pegawai);
        $this->assertEquals($data['nama_unit_kerja'], $riwayat->nama_unit_kerja);
        $this->assertEquals($data['alamat_unit_kerja'], $riwayat->alamat_unit_kerja);
        $this->assertEquals($data['jabatan'], $riwayat->jabatan);
        $this->assertEquals($data['tanggal_mulai'], $riwayat->tanggal_mulai->format('Y-m-d'));
        $this->assertEquals($data['tanggal_selesai'], $riwayat->tanggal_selesai->format('Y-m-d'));
        $this->assertTrue($riwayat->is_bekerja_di_tempat_lain);
        $this->assertEquals($data['status'], $riwayat->status);
    }

    /** @test */
    public function it_belongs_to_data_pegawai()
    {
        $riwayat = DataRiwayatUnitKerjaLain::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik,
        ]);

        $this->assertInstanceOf(DataPegawai::class, $riwayat->dataPegawai);
        $this->assertEquals($this->pegawai->nik, $riwayat->dataPegawai->nik);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $fillable = [
            'nik_data_pegawai',
            'nama_unit_kerja',
            'alamat_unit_kerja',
            'jabatan',
            'tanggal_mulai',
            'tanggal_selesai',
            'is_bekerja_di_tempat_lain',
            'status',
        ];

        $model = new DataRiwayatUnitKerjaLain();
        $this->assertEquals($fillable, $model->getFillable());
    }

    /** @test */
    public function it_casts_dates_correctly()
    {
        $riwayat = DataRiwayatUnitKerjaLain::factory()->create([
            'tanggal_mulai' => '2023-01-01',
            'tanggal_selesai' => '2023-12-31',
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $riwayat->tanggal_mulai);
        $this->assertInstanceOf(\Carbon\Carbon::class, $riwayat->tanggal_selesai);
    }

    /** @test */
    public function it_casts_boolean_correctly()
    {
        $riwayat = DataRiwayatUnitKerjaLain::factory()->create([
            'is_bekerja_di_tempat_lain' => 1,
        ]);

        $this->assertTrue($riwayat->is_bekerja_di_tempat_lain);
        $this->assertIsBool($riwayat->is_bekerja_di_tempat_lain);
    }

    /** @test */
    public function it_has_correct_table_name()
    {
        $model = new DataRiwayatUnitKerjaLain();
        $this->assertEquals('data_riwayat_unit_kerja_lain', $model->getTable());
    }

    /** @test */
    public function it_uses_correct_primary_key()
    {
        $model = new DataRiwayatUnitKerjaLain();
        $this->assertEquals('id', $model->getKeyName());
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        DataRiwayatUnitKerjaLain::create([]);
    }

    /** @test */
    public function it_can_filter_by_status()
    {
        DataRiwayatUnitKerjaLain::factory()->create(['status' => 'aktif']);
        DataRiwayatUnitKerjaLain::factory()->create(['status' => 'tidak_aktif']);
        DataRiwayatUnitKerjaLain::factory()->create(['status' => 'selesai']);

        $aktif = DataRiwayatUnitKerjaLain::where('status', 'aktif')->count();
        $tidakAktif = DataRiwayatUnitKerjaLain::where('status', 'tidak_aktif')->count();
        $selesai = DataRiwayatUnitKerjaLain::where('status', 'selesai')->count();

        $this->assertEquals(1, $aktif);
        $this->assertEquals(1, $tidakAktif);
        $this->assertEquals(1, $selesai);
    }

    /** @test */
    public function it_can_filter_by_working_status()
    {
        DataRiwayatUnitKerjaLain::factory()->create(['is_bekerja_di_tempat_lain' => true]);
        DataRiwayatUnitKerjaLain::factory()->create(['is_bekerja_di_tempat_lain' => false]);

        $bekerja = DataRiwayatUnitKerjaLain::where('is_bekerja_di_tempat_lain', true)->count();
        $tidakBekerja = DataRiwayatUnitKerjaLain::where('is_bekerja_di_tempat_lain', false)->count();

        $this->assertEquals(1, $bekerja);
        $this->assertEquals(1, $tidakBekerja);
    }
}