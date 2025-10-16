<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\JenjangPendidikan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JenjangPendidikanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test JenjangPendidikan model creation.
     */
    public function test_jenjang_pendidikan_can_be_created(): void
    {
        $jenjangPendidikan = JenjangPendidikan::create([
            'kode' => 'S1',
            'nama_jenjang_pendidikan' => 'Sarjana',
            'status' => '1',
            'urut' => 1
        ]);

        $this->assertInstanceOf(JenjangPendidikan::class, $jenjangPendidikan);
        $this->assertEquals('S1', $jenjangPendidikan->kode);
        $this->assertEquals('Sarjana', $jenjangPendidikan->nama_jenjang_pendidikan);
        $this->assertEquals('1', $jenjangPendidikan->status);
        $this->assertEquals(1, $jenjangPendidikan->urut);
    }

    /**
     * Test JenjangPendidikan model fillable fields.
     */
    public function test_jenjang_pendidikan_fillable_fields(): void
    {
        $jenjangPendidikan = new JenjangPendidikan();
        $fillable = $jenjangPendidikan->getFillable();

        $expectedFillable = ['kode', 'nama_jenjang_pendidikan', 'status', 'urut'];

        $this->assertEquals($expectedFillable, $fillable);
    }

    /**
     * Test JenjangPendidikan model factory.
     */
    public function test_jenjang_pendidikan_factory(): void
    {
        $jenjangPendidikan = JenjangPendidikan::factory()->create();

        $this->assertInstanceOf(JenjangPendidikan::class, $jenjangPendidikan);
        $this->assertNotEmpty($jenjangPendidikan->kode);
        $this->assertNotEmpty($jenjangPendidikan->nama_jenjang_pendidikan);
        $this->assertContains($jenjangPendidikan->status, ['1', '0']);
        $this->assertIsInt($jenjangPendidikan->urut);
    }

    /**
     * Test JenjangPendidikan model validation.
     */
    public function test_jenjang_pendidikan_requires_required_fields(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        JenjangPendidikan::create([]);
    }
}
