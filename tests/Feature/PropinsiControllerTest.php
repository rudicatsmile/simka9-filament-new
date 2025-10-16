<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\TabelPropinsi;

class PropinsiControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test index endpoint returns paginated propinsi data.
     */
    public function test_index_returns_paginated_propinsi_data(): void
    {
        // Create test data
        TabelPropinsi::factory()->count(5)->create();

        $response = $this->getJson('/api/propinsi');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'kode',
                                'nama_propinsi',
                                'status',
                                'urut',
                                'created_at',
                                'updated_at'
                            ]
                        ],
                        'current_page',
                        'per_page',
                        'total'
                    ]
                ]);
    }

    /**
     * Test store endpoint creates new propinsi.
     */
    public function test_store_creates_new_propinsi(): void
    {
        $propinsiData = [
            'kode' => '99',
            'nama_propinsi' => 'Test Propinsi',
            'status' => '1',
            'urut' => 99
        ];

        $response = $this->postJson('/api/propinsi', $propinsiData);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Data propinsi berhasil disimpan'
                ]);

        $this->assertDatabaseHas('tabel_propinsi', $propinsiData);
    }

    /**
     * Test store endpoint validates required fields.
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->postJson('/api/propinsi', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['kode', 'nama_propinsi', 'urut']);
    }

    /**
     * Test show endpoint returns specific propinsi.
     */
    public function test_show_returns_specific_propinsi(): void
    {
        $propinsi = TabelPropinsi::factory()->create();

        $response = $this->getJson("/api/propinsi/{$propinsi->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'id' => $propinsi->id,
                        'kode' => $propinsi->kode,
                        'nama_propinsi' => $propinsi->nama_propinsi,
                        'status' => $propinsi->status,
                        'urut' => $propinsi->urut
                    ]
                ]);
    }

    /**
     * Test show endpoint returns 404 for non-existent propinsi.
     */
    public function test_show_returns_404_for_non_existent_propinsi(): void
    {
        $response = $this->getJson('/api/propinsi/999');

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Data propinsi tidak ditemukan'
                ]);
    }

    /**
     * Test update endpoint updates existing propinsi.
     */
    public function test_update_updates_existing_propinsi(): void
    {
        $propinsi = TabelPropinsi::factory()->create();
        
        $updateData = [
            'kode' => '88',
            'nama_propinsi' => 'Updated Propinsi',
            'status' => '0',
            'urut' => 88
        ];

        $response = $this->putJson("/api/propinsi/{$propinsi->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Data propinsi berhasil diperbarui'
                ]);

        $this->assertDatabaseHas('tabel_propinsi', array_merge(['id' => $propinsi->id], $updateData));
    }

    /**
     * Test update endpoint validates required fields.
     */
    public function test_update_validates_required_fields(): void
    {
        $propinsi = TabelPropinsi::factory()->create();

        $response = $this->putJson("/api/propinsi/{$propinsi->id}", []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['kode', 'nama_propinsi', 'urut']);
    }

    /**
     * Test destroy endpoint deletes propinsi.
     */
    public function test_destroy_deletes_propinsi(): void
    {
        $propinsi = TabelPropinsi::factory()->create();

        $response = $this->deleteJson("/api/propinsi/{$propinsi->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Data propinsi berhasil dihapus'
                ]);

        $this->assertDatabaseMissing('tabel_propinsi', ['id' => $propinsi->id]);
    }

    /**
     * Test destroy endpoint returns 500 for non-existent propinsi.
     */
    public function test_destroy_returns_500_for_non_existent_propinsi(): void
    {
        $response = $this->deleteJson('/api/propinsi/999');

        $response->assertStatus(500)
                ->assertJson([
                    'success' => false,
                    'message' => 'Gagal menghapus data propinsi'
                ]);
    }

    /**
     * Test index endpoint supports search functionality.
     */
    public function test_index_supports_search(): void
    {
        TabelPropinsi::factory()->create(['nama_propinsi' => 'Aceh']);
        TabelPropinsi::factory()->create(['nama_propinsi' => 'Bali']);

        $response = $this->getJson('/api/propinsi?search=Aceh');

        $response->assertStatus(200);
        
        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertEquals('Aceh', $data[0]['nama_propinsi']);
    }
}
