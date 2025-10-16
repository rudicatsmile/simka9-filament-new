<?php

namespace Tests\Unit;

use App\Models\TabelPekerjaan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests for TabelPekerjaan model
 * 
 * @package Tests\Unit
 * @author Laravel Test Suite
 * @version 1.0.0
 */
class TabelPekerjaanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test TabelPekerjaan model can be created
     * 
     * @return void
     */
    public function test_tabel_pekerjaan_can_be_created(): void
    {
        $tabelPekerjaan = TabelPekerjaan::factory()->create();

        $this->assertInstanceOf(TabelPekerjaan::class, $tabelPekerjaan);
        $this->assertDatabaseHas('tabel_pekerjaan', [
            'id' => $tabelPekerjaan->id,
            'kode' => $tabelPekerjaan->kode,
            'nama_pekerjaan' => $tabelPekerjaan->nama_pekerjaan,
        ]);
    }

    /**
     * Test TabelPekerjaan fillable attributes
     * 
     * @return void
     */
    public function test_tabel_pekerjaan_fillable_attributes(): void
    {
        $fillable = [
            'kode',
            'nama_pekerjaan',
            'status',
            'urut'
        ];

        $tabelPekerjaan = new TabelPekerjaan();
        $this->assertEquals($fillable, $tabelPekerjaan->getFillable());
    }

    /**
     * Test TabelPekerjaan factory creates valid data
     * 
     * @return void
     */
    public function test_tabel_pekerjaan_factory_creates_valid_data(): void
    {
        $tabelPekerjaan = TabelPekerjaan::factory()->create();

        $this->assertNotEmpty($tabelPekerjaan->kode);
        $this->assertNotEmpty($tabelPekerjaan->nama_pekerjaan);
        $this->assertContains($tabelPekerjaan->status, ['1', '0']);
        $this->assertIsInt($tabelPekerjaan->urut);
    }

    /**
     * Test TabelPekerjaan table name
     * 
     * @return void
     */
    public function test_tabel_pekerjaan_table_name(): void
    {
        $tabelPekerjaan = new TabelPekerjaan();
        $this->assertEquals('tabel_pekerjaan', $tabelPekerjaan->getTable());
    }

    /**
     * Test TabelPekerjaan casts attributes correctly
     * 
     * @return void
     */
    public function test_tabel_pekerjaan_casts_attributes(): void
    {
        $tabelPekerjaan = TabelPekerjaan::factory()->create([
            'urut' => '10'
        ]);

        $this->assertIsInt($tabelPekerjaan->urut);
        $this->assertEquals(10, $tabelPekerjaan->urut);
    }
}