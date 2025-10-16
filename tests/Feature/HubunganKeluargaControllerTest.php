<?php

namespace Tests\Feature;

use App\Models\TabelHubunganKeluarga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Feature tests for HubunganKeluargaController
 * 
 * @package Tests\Feature
 * @author Laravel Test Suite
 * @version 1.0.0
 */
class HubunganKeluargaControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test index method returns paginated data
     * 
     * @return void
     */
    public function test_index_returns_paginated_data(): void
    {
        TabelHubunganKeluarga::factory()->count(5)->create();

        $response = $this->getJson('/api/hubungan-keluarga');

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
     * Test store method creates new hubungan keluarga
     * 
     * @return void
     */
    public function test_store_creates_new_hubungan_keluarga(): void
    {
        $data = [
            'kode' => 'HK001',
            'nama_hubungan_keluarga' => 'Test Hubungan Keluarga',
            'status' => '1',
            'urut' => 1
        ];

        $response = $this->postJson('/api/hubungan-keluarga', $data);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Data hubungan keluarga berhasil disimpan'
                ]);

        $this->assertDatabaseHas('tabel_hubungan_keluarga', $data);
    }

    /**
     * Test store method validates required fields
     * 
     * @return void
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->postJson('/api/hubungan-keluarga', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['kode', 'nama_hubungan_keluarga', 'status', 'urut']);
    }

    /**
     * Test show method returns specific hubungan keluarga
     * 
     * @return void
     */
    public function test_show_returns_specific_hubungan_keluarga(): void
    {
        $hubunganKeluarga = TabelHubunganKeluarga::factory()->create();

        $response = $this->getJson("/api/hubungan-keluarga/{$hubunganKeluarga->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'id' => $hubunganKeluarga->id,
                        'kode' => $hubunganKeluarga->kode,
                        'nama_hubungan_keluarga' => $hubunganKeluarga->nama_hubungan_keluarga
                    ]
                ]);
    }

    /**
     * Test update method updates existing hubungan keluarga
     * 
     * @return void
     */
    public function test_update_modifies_existing_hubungan_keluarga(): void
    {
        $hubunganKeluarga = TabelHubunganKeluarga::factory()->create();
        
        $updateData = [
            'kode' => 'HK002',
            'nama_hubungan_keluarga' => 'Updated Hubungan Keluarga',
            'status' => '0',
            'urut' => 2
        ];

        $response = $this->putJson("/api/hubungan-keluarga/{$hubunganKeluarga->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Data hubungan keluarga berhasil diperbarui'
                ]);

        $this->assertDatabaseHas('tabel_hubungan_keluarga', array_merge(['id' => $hubunganKeluarga->id], $updateData));
    }

    /**
     * Test destroy method deletes hubungan keluarga
     * 
     * @return void
     */
    public function test_destroy_deletes_hubungan_keluarga(): void
    {
        $hubunganKeluarga = TabelHubunganKeluarga::factory()->create();

        $response = $this->deleteJson("/api/hubungan-keluarga/{$hubunganKeluarga->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Data hubungan keluarga berhasil dihapus'
                ]);

        $this->assertDatabaseMissing('tabel_hubungan_keluarga', ['id' => $hubunganKeluarga->id]);
    }

    /**
     * Test show method returns 404 for non-existent hubungan keluarga
     * 
     * @return void
     */
    public function test_show_returns_404_for_non_existent_hubungan_keluarga(): void
    {
        $response = $this->getJson('/api/hubungan-keluarga/999');

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Data hubungan keluarga tidak ditemukan'
                ]);
    }

    /**
     * Test index method with search functionality
     * 
     * @return void
     */
    public function test_index_with_search_functionality(): void
    {
        TabelHubunganKeluarga::factory()->create(['nama_hubungan_keluarga' => 'Ayah']);
        TabelHubunganKeluarga::factory()->create(['nama_hubungan_keluarga' => 'Ibu']);

        $response = $this->getJson('/api/hubungan-keluarga?search=Ayah');

        $response->assertStatus(200)
                ->assertJsonFragment(['nama_hubungan_keluarga' => 'Ayah']);
    }

    /**
     * Test store method validates unique kode
     * 
     * @return void
     */
    public function test_store_validates_unique_kode(): void
    {
        $existingHubunganKeluarga = TabelHubunganKeluarga::factory()->create(['kode' => 'HK001']);

        $data = [
            'kode' => 'HK001',
            'nama_hubungan_keluarga' => 'Test Hubungan Keluarga',
            'status' => '1',
            'urut' => 1
        ];

        $response = $this->postJson('/api/hubungan-keluarga', $data);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['kode']);
    }
}