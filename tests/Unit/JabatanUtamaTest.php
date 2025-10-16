<?php

namespace Tests\Unit;

use App\Models\JabatanUtama;
use App\Models\UnitKerja;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests for JabatanUtama model
 */
class JabatanUtamaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test JabatanUtama model can be created
     */
    public function test_jabatan_utama_can_be_created(): void
    {
        $unitKerja = UnitKerja::factory()->create();
        
        $jabatanUtama = JabatanUtama::factory()->create([
            'kode_unit_kerja' => $unitKerja->kode,
        ]);

        $this->assertInstanceOf(JabatanUtama::class, $jabatanUtama);
        $this->assertDatabaseHas('jabatan_utama', [
            'id' => $jabatanUtama->id,
            'kode' => $jabatanUtama->kode,
            'kode_unit_kerja' => $unitKerja->kode,
        ]);
    }

    /**
     * Test JabatanUtama belongs to UnitKerja relationship
     */
    public function test_jabatan_utama_belongs_to_unit_kerja(): void
    {
        $unitKerja = UnitKerja::factory()->create();
        $jabatanUtama = JabatanUtama::factory()->create([
            'kode_unit_kerja' => $unitKerja->kode,
        ]);

        $this->assertInstanceOf(UnitKerja::class, $jabatanUtama->unitKerja);
        $this->assertEquals($unitKerja->kode, $jabatanUtama->unitKerja->kode);
    }

    /**
     * Test JabatanUtama fillable attributes
     */
    public function test_jabatan_utama_fillable_attributes(): void
    {
        $fillable = [
            'kode',
            'kode_unit_kerja',
            'nama_jabatan_utama',
            'status',
            'urut'
        ];

        $jabatanUtama = new JabatanUtama();
        $this->assertEquals($fillable, $jabatanUtama->getFillable());
    }

    /**
     * Test JabatanUtama factory creates valid data
     */
    public function test_jabatan_utama_factory_creates_valid_data(): void
    {
        $unitKerja = UnitKerja::factory()->create();
        $jabatanUtama = JabatanUtama::factory()->create([
            'kode_unit_kerja' => $unitKerja->kode,
        ]);

        $this->assertNotEmpty($jabatanUtama->kode);
        $this->assertNotEmpty($jabatanUtama->nama_jabatan_utama);
        $this->assertContains($jabatanUtama->status, ['1', '0']);
        $this->assertIsInt($jabatanUtama->urut);
        $this->assertEquals($unitKerja->kode, $jabatanUtama->kode_unit_kerja);
    }

    /**
     * Test JabatanUtama table name
     */
    public function test_jabatan_utama_table_name(): void
    {
        $jabatanUtama = new JabatanUtama();
        $this->assertEquals('jabatan_utama', $jabatanUtama->getTable());
    }
}
