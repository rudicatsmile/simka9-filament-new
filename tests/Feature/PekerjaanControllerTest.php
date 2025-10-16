<?php

namespace Tests\Feature;

use App\Models\TabelPekerjaan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Feature tests for PekerjaanController
 * 
 * @package Tests\Feature
 * @author Laravel Test Suite
 * @version 1.0.0
 */
class PekerjaanControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test index method returns paginated data
     * 
     * @return void
     */
    public function test_index_returns_paginated_data(): void
    {
        TabelPekerjaan::factory()->count(5)->create();

        $response = $this->getJson('/api/pekerjaan');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'data',
                        'current_page',
                        'per_page',
                        'total'
                    ]
                ]);
    }

    /**
     * Test store method creates new pekerjaan
     * 
     * @return void
     */
    public function test_store_creates_new_pekerjaan(): void
    {
        $data = [
            'kode' => 'PK001',
            'nama_pekerjaan' => 'Test Pekerjaan',
            'status' => '1',
            'urut' => 1
        ];

        $response = $this->postJson('/api/pekerjaan', $data);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Pekerjaan created successfully'
                ]);

        $this->assertDatabaseHas('tabel_pekerjaan', $data);
    }

    /**
     * Test store method validates required fields
     * 
     * @return void
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->postJson('/api/pekerjaan', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['kode', 'nama_pekerjaan', 'status', 'urut']);
    }

    /**
     * Test show method returns specific pekerjaan
     * 
     * @return void
     */
    public function test_show_returns_specific_pekerjaan(): void
    {
        $pekerjaan = TabelPekerjaan::factory()->create();

        $response = $this->getJson("/api/pekerjaan/{$pekerjaan->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'id' => $pekerjaan->id,
                        'kode' => $pekerjaan->kode,
                        'nama_pekerjaan' => $pekerjaan->nama_pekerjaan
                    ]
                ]);
    }

    /**
     * Test update method updates existing pekerjaan
     * 
     * @return void
     */
    public function test_update_modifies_existing_pekerjaan(): void
    {
        $pekerjaan = TabelPekerjaan::factory()->create();
        
        $updateData = [
            'kode' => 'PK002',
            'nama_pekerjaan' => 'Updated Pekerjaan',
            'status' => '0',
            'urut' => 2
        ];

        $response = $this->putJson("/api/pekerjaan/{$pekerjaan->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Pekerjaan updated successfully'
                ]);

        $this->assertDatabaseHas('tabel_pekerjaan', array_merge(['id' => $pekerjaan->id], $updateData));
    }

    /**
     * Test destroy method deletes pekerjaan
     * 
     * @return void
     */
    public function test_destroy_deletes_pekerjaan(): void
    {
        $pekerjaan = TabelPekerjaan::factory()->create();

        $response = $this->deleteJson("/api/pekerjaan/{$pekerjaan->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Pekerjaan deleted successfully'
                ]);

        $this->assertDatabaseMissing('tabel_pekerjaan', ['id' => $pekerjaan->id]);
    }

    /**
     * Test show method returns 404 for non-existent pekerjaan
     * 
     * @return void
     */
    public function test_show_returns_404_for_non_existent_pekerjaan(): void
    {
        $response = $this->getJson('/api/pekerjaan/999');

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Pekerjaan not found'
                ]);
    }
}