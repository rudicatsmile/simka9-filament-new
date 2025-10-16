<?php

namespace Tests\Feature;

use App\Models\TabelJenisPelatihan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Feature tests for JenisPelatihanController
 * 
 * @package Tests\Feature
 * @author Laravel Test Suite
 * @version 1.0.0
 */
class JenisPelatihanControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test index method returns paginated data
     * 
     * @return void
     */
    public function test_index_returns_paginated_data(): void
    {
        TabelJenisPelatihan::factory()->count(5)->create();

        $response = $this->getJson('/api/jenis-pelatihan');

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
     * Test store method creates new jenis pelatihan
     * 
     * @return void
     */
    public function test_store_creates_new_jenis_pelatihan(): void
    {
        $data = [
            'kode' => 'JP001',
            'nama_jenis_pelatihan' => 'Test Jenis Pelatihan',
            'status' => '1',
            'urut' => 1
        ];

        $response = $this->postJson('/api/jenis-pelatihan', $data);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Jenis Pelatihan created successfully'
                ]);

        $this->assertDatabaseHas('tabel_jenis_pelatihan', $data);
    }

    /**
     * Test store method validates required fields
     * 
     * @return void
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->postJson('/api/jenis-pelatihan', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['kode', 'nama_jenis_pelatihan', 'status', 'urut']);
    }

    /**
     * Test show method returns specific jenis pelatihan
     * 
     * @return void
     */
    public function test_show_returns_specific_jenis_pelatihan(): void
    {
        $jenisPelatihan = TabelJenisPelatihan::factory()->create();

        $response = $this->getJson("/api/jenis-pelatihan/{$jenisPelatihan->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'id' => $jenisPelatihan->id,
                        'kode' => $jenisPelatihan->kode,
                        'nama_jenis_pelatihan' => $jenisPelatihan->nama_jenis_pelatihan
                    ]
                ]);
    }

    /**
     * Test update method updates existing jenis pelatihan
     * 
     * @return void
     */
    public function test_update_modifies_existing_jenis_pelatihan(): void
    {
        $jenisPelatihan = TabelJenisPelatihan::factory()->create();
        
        $updateData = [
            'kode' => 'JP002',
            'nama_jenis_pelatihan' => 'Updated Jenis Pelatihan',
            'status' => '0',
            'urut' => 2
        ];

        $response = $this->putJson("/api/jenis-pelatihan/{$jenisPelatihan->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Jenis Pelatihan updated successfully'
                ]);

        $this->assertDatabaseHas('tabel_jenis_pelatihan', array_merge(['id' => $jenisPelatihan->id], $updateData));
    }

    /**
     * Test destroy method deletes jenis pelatihan
     * 
     * @return void
     */
    public function test_destroy_deletes_jenis_pelatihan(): void
    {
        $jenisPelatihan = TabelJenisPelatihan::factory()->create();

        $response = $this->deleteJson("/api/jenis-pelatihan/{$jenisPelatihan->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Jenis Pelatihan deleted successfully'
                ]);

        $this->assertDatabaseMissing('tabel_jenis_pelatihan', ['id' => $jenisPelatihan->id]);
    }

    /**
     * Test show method returns 404 for non-existent jenis pelatihan
     * 
     * @return void
     */
    public function test_show_returns_404_for_non_existent_jenis_pelatihan(): void
    {
        $response = $this->getJson('/api/jenis-pelatihan/999');

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Jenis Pelatihan not found'
                ]);
    }
}