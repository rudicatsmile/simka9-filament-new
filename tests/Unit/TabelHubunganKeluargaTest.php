<?php

namespace Tests\Unit;

use App\Models\TabelHubunganKeluarga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests for TabelHubunganKeluarga model
 * 
 * @package Tests\Unit
 * @author Laravel Test Suite
 * @version 1.0.0
 */
class TabelHubunganKeluargaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test TabelHubunganKeluarga model can be created
     * 
     * @return void
     */
    public function test_tabel_hubungan_keluarga_can_be_created(): void
    {
        $tabelHubunganKeluarga = TabelHubunganKeluarga::factory()->create();

        $this->assertInstanceOf(TabelHubunganKeluarga::class, $tabelHubunganKeluarga);
        $this->assertDatabaseHas('tabel_hubungan_keluarga', [
            'id' => $tabelHubunganKeluarga->id,
            'kode' => $tabelHubunganKeluarga->kode,
            'nama_hubungan_keluarga' => $tabelHubunganKeluarga->nama_hubungan_keluarga,
        ]);
    }

    /**
     * Test TabelHubunganKeluarga fillable attributes
     * 
     * @return void
     */
    public function test_tabel_hubungan_keluarga_fillable_attributes(): void
    {
        $fillable = [
            'kode',
            'nama_hubungan_keluarga',
            'status',
            'urut'
        ];

        $tabelHubunganKeluarga = new TabelHubunganKeluarga();
        $this->assertEquals($fillable, $tabelHubunganKeluarga->getFillable());
    }

    /**
     * Test TabelHubunganKeluarga factory creates valid data
     * 
     * @return void
     */
    public function test_tabel_hubungan_keluarga_factory_creates_valid_data(): void
    {
        $tabelHubunganKeluarga = TabelHubunganKeluarga::factory()->create();

        $this->assertNotEmpty($tabelHubunganKeluarga->kode);
        $this->assertNotEmpty($tabelHubunganKeluarga->nama_hubungan_keluarga);
        $this->assertContains($tabelHubunganKeluarga->status, ['1', '0']);
        $this->assertIsInt($tabelHubunganKeluarga->urut);
    }

    /**
     * Test TabelHubunganKeluarga table name
     * 
     * @return void
     */
    public function test_tabel_hubungan_keluarga_table_name(): void
    {
        $tabelHubunganKeluarga = new TabelHubunganKeluarga();
        $this->assertEquals('tabel_hubungan_keluarga', $tabelHubunganKeluarga->getTable());
    }

    /**
     * Test TabelHubunganKeluarga casts attributes correctly
     * 
     * @return void
     */
    public function test_tabel_hubungan_keluarga_casts_attributes(): void
    {
        $tabelHubunganKeluarga = TabelHubunganKeluarga::factory()->create([
            'urut' => '10'
        ]);

        $this->assertIsInt($tabelHubunganKeluarga->urut);
        $this->assertEquals(10, $tabelHubunganKeluarga->urut);
    }

    /**
     * Test TabelHubunganKeluarga status enum validation
     * 
     * @return void
     */
    public function test_tabel_hubungan_keluarga_status_enum(): void
    {
        $activeHubunganKeluarga = TabelHubunganKeluarga::factory()->create(['status' => '1']);
        $inactiveHubunganKeluarga = TabelHubunganKeluarga::factory()->create(['status' => '0']);

        $this->assertEquals('1', $activeHubunganKeluarga->status);
        $this->assertEquals('0', $inactiveHubunganKeluarga->status);
    }
}