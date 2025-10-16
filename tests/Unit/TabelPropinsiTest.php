<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\TabelPropinsi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TabelPropinsiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that TabelPropinsi model can be created with valid data.
     */
    public function test_can_create_tabel_propinsi(): void
    {
        $propinsi = TabelPropinsi::create([
            'kode' => '11',
            'nama_propinsi' => 'Aceh',
            'status' => '1',
            'urut' => 1
        ]);

        $this->assertInstanceOf(TabelPropinsi::class, $propinsi);
        $this->assertEquals('11', $propinsi->kode);
        $this->assertEquals('Aceh', $propinsi->nama_propinsi);
        $this->assertEquals('1', $propinsi->status);
        $this->assertEquals(1, $propinsi->urut);
    }

    /**
     * Test that status field is cast to string.
     */
    public function test_status_is_cast_to_string(): void
    {
        $propinsi = TabelPropinsi::create([
            'kode' => '12',
            'nama_propinsi' => 'Sumatera Utara',
            'status' => 1, // Integer input
            'urut' => 2
        ]);

        $this->assertIsString($propinsi->status);
        $this->assertEquals('1', $propinsi->status);
    }

    /**
     * Test that status mutator works correctly.
     */
    public function test_status_mutator_converts_to_string(): void
    {
        $propinsi = new TabelPropinsi();
        $propinsi->status = 0; // Integer input
        
        $this->assertEquals('0', $propinsi->status);
    }

    /**
     * Test fillable attributes.
     */
    public function test_fillable_attributes(): void
    {
        $fillable = ['kode', 'nama_propinsi', 'status', 'urut'];
        $propinsi = new TabelPropinsi();
        
        $this->assertEquals($fillable, $propinsi->getFillable());
    }

    /**
     * Test table name.
     */
    public function test_table_name(): void
    {
        $propinsi = new TabelPropinsi();
        $this->assertEquals('tabel_propinsi', $propinsi->getTable());
    }

    /**
     * Test model uses HasFactory trait.
     */
    public function test_model_uses_has_factory_trait(): void
    {
        $this->assertTrue(method_exists(TabelPropinsi::class, 'factory'));
    }
}
