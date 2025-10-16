<?php

namespace Tests\Unit;

use App\Models\TabelJenisPelatihan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests for TabelJenisPelatihan model
 * 
 * @package Tests\Unit
 * @author Laravel Test Suite
 * @version 1.0.0
 */
class TabelJenisPelatihanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test TabelJenisPelatihan model can be created
     * 
     * @return void
     */
    public function test_tabel_jenis_pelatihan_can_be_created(): void
    {
        $tabelJenisPelatihan = TabelJenisPelatihan::factory()->create();

        $this->assertInstanceOf(TabelJenisPelatihan::class, $tabelJenisPelatihan);
        $this->assertDatabaseHas('tabel_jenis_pelatihan', [
            'id' => $tabelJenisPelatihan->id,
            'kode' => $tabelJenisPelatihan->kode,
            'nama_jenis_pelatihan' => $tabelJenisPelatihan->nama_jenis_pelatihan,
        ]);
    }

    /**
     * Test TabelJenisPelatihan fillable attributes
     * 
     * @return void
     */
    public function test_tabel_jenis_pelatihan_fillable_attributes(): void
    {
        $fillable = [
            'kode',
            'nama_jenis_pelatihan',
            'status',
            'urut'
        ];

        $tabelJenisPelatihan = new TabelJenisPelatihan();
        $this->assertEquals($fillable, $tabelJenisPelatihan->getFillable());
    }

    /**
     * Test TabelJenisPelatihan factory creates valid data
     * 
     * @return void
     */
    public function test_tabel_jenis_pelatihan_factory_creates_valid_data(): void
    {
        $tabelJenisPelatihan = TabelJenisPelatihan::factory()->create();

        $this->assertNotEmpty($tabelJenisPelatihan->kode);
        $this->assertNotEmpty($tabelJenisPelatihan->nama_jenis_pelatihan);
        $this->assertContains($tabelJenisPelatihan->status, ['1', '0']);
        $this->assertIsInt($tabelJenisPelatihan->urut);
    }

    /**
     * Test TabelJenisPelatihan table name
     * 
     * @return void
     */
    public function test_tabel_jenis_pelatihan_table_name(): void
    {
        $tabelJenisPelatihan = new TabelJenisPelatihan();
        $this->assertEquals('tabel_jenis_pelatihan', $tabelJenisPelatihan->getTable());
    }

    /**
     * Test TabelJenisPelatihan casts attributes correctly
     * 
     * @return void
     */
    public function test_tabel_jenis_pelatihan_casts_attributes(): void
    {
        $tabelJenisPelatihan = TabelJenisPelatihan::factory()->create([
            'urut' => '10'
        ]);

        $this->assertIsInt($tabelJenisPelatihan->urut);
        $this->assertEquals(10, $tabelJenisPelatihan->urut);
    }
}