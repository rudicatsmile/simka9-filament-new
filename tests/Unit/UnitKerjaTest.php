<?php

namespace Tests\Unit;

use App\Models\UnitKerja;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitKerjaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_unit_kerja()
    {
        $unitKerja = UnitKerja::create([
            'kode' => 'KS',
            'nama_unit_kerja' => 'Kepala Sekolah',
            'status' => '1',
            'urut' => 1,
        ]);

        $this->assertInstanceOf(UnitKerja::class, $unitKerja);
        $this->assertEquals('KS', $unitKerja->kode);
        $this->assertEquals('Kepala Sekolah', $unitKerja->nama_unit_kerja);
        $this->assertEquals('1', $unitKerja->status);
        $this->assertEquals(1, $unitKerja->urut);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = ['kode', 'nama_unit_kerja', 'status', 'urut'];
        $unitKerja = new UnitKerja();

        $this->assertEquals($fillable, $unitKerja->getFillable());
    }

    /** @test */
    public function it_can_use_factory()
    {
        $unitKerja = UnitKerja::factory()->create();

        $this->assertInstanceOf(UnitKerja::class, $unitKerja);
        $this->assertNotNull($unitKerja->kode);
        $this->assertNotNull($unitKerja->nama_unit_kerja);
        $this->assertNotNull($unitKerja->status);
        $this->assertNotNull($unitKerja->urut);
    }

    /** @test */
    public function it_requires_all_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        UnitKerja::create([]);
    }
}