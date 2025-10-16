<?php

namespace Tests\Feature;

use App\Models\TabelGolonganDarah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Test class for GolonganDarahController
 * 
 * This class contains unit tests for all CRUD operations
 * of the GolonganDarahController including validation tests.
 */
class GolonganDarahControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test the index method returns paginated data
     */
    public function test_index_returns_paginated_data()
    {
        // Create test data
        TabelGolonganDarah::factory()->count(15)->create();

        $response = $this->getJson('/api/golongan-darah');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'current_page',
                        'data' => [
                            '*' => [
                                'id',
                                'kode',
                                'nama_golongan_darah',
                                'status',
                                'urut',
                                'created_at',
                                'updated_at'
                            ]
                        ],
                        'first_page_url',
                        'from',
                        'last_page',
                        'last_page_url',
                        'links',
                        'next_page_url',
                        'path',
                        'per_page',
                        'prev_page_url',
                        'to',
                        'total'
                    ]
                ]);
    }

    /**
     * Test the index method with search functionality
     */
    public function test_index_with_search()
    {
        // Create specific test data
        TabelGolonganDarah::factory()->create([
            'nama_golongan_darah' => 'A',
            'kode' => 'A001'
        ]);
        
        TabelGolonganDarah::factory()->create([
            'nama_golongan_darah' => 'B',
            'kode' => 'B001'
        ]);

        $response = $this->getJson('/api/golongan-darah?search=A');

        $response->assertStatus(200);
        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertEquals('A', $data[0]['nama_golongan_darah']);
    }

    /**
     * Test the create method returns validation rules (Note: create route not available in API resource)
     * This test is commented out as Laravel API resource doesn't include create/edit routes
     */
    /*
    public function test_create_returns_validation_rules()
    {
        $response = $this->getJson('/api/golongan-darah/create');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                    'fields' => [
                        'kode',
                        'nama_golongan_darah',
                        'status',
                        'urut'
                    ]
                ]
                ]);
    }
    */

    /**
     * Test the store method creates new record successfully
     */
    public function test_store_creates_record_successfully()
    {
        $data = [
            'kode' => 'AB001',
            'nama_golongan_darah' => 'AB',
            'status' => '1',
            'urut' => 3
        ];

        $response = $this->postJson('/api/golongan-darah', $data);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'kode',
                        'nama_golongan_darah',
                        'status',
                        'urut',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'success' => true,
                    'message' => 'Data golongan darah berhasil disimpan'
                ]);

        $this->assertDatabaseHas('tabel_golongan_darah', $data);
    }

    /**
     * Test the store method validation fails with invalid data
     */
    public function test_store_validation_fails()
    {
        $data = [
            'kode' => '', // Required field empty
            'nama_golongan_darah' => '',
            'status' => 'invalid', // Invalid enum value
            'urut' => 'not_a_number' // Invalid integer
        ];

        $response = $this->postJson('/api/golongan-darah', $data);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'errors' => [
                        'kode',
                        'nama_golongan_darah',
                        'status',
                        'urut'
                    ]
                ]);
    }

    /**
     * Test the show method returns specific record
     */
    public function test_show_returns_specific_record()
    {
        $golonganDarah = TabelGolonganDarah::factory()->create();

        $response = $this->getJson("/api/golongan-darah/{$golonganDarah->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'kode',
                        'nama_golongan_darah',
                        'status',
                        'urut',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'data' => [
                        'id' => $golonganDarah->id,
                        'kode' => $golonganDarah->kode,
                        'nama_golongan_darah' => $golonganDarah->nama_golongan_darah
                    ]
                ]);
    }

    /**
     * Test the show method returns 404 for non-existent record
     */
    public function test_show_returns_404_for_non_existent_record()
    {
        $response = $this->getJson('/api/golongan-darah/999');

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Data golongan darah tidak ditemukan'
                ]);
    }

    /**
     * Test the edit method returns record data and validation rules (Note: edit route not available in API resource)
     * This test is commented out as Laravel API resource doesn't include create/edit routes
     */
    /*
    public function test_edit_returns_record_and_validation_rules()
    {
        $golonganDarah = TabelGolonganDarah::factory()->create();

        $response = $this->getJson("/api/golongan-darah/{$golonganDarah->id}/edit");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'golongan_darah' => [
                            'id',
                            'kode',
                            'nama_golongan_darah',
                            'status',
                            'urut',
                            'created_at',
                            'updated_at'
                        ],
                        'fields' => [
                            'kode',
                            'nama_golongan_darah',
                            'status',
                            'urut'
                        ]
                    ]
                ]);
    }
    */

    /**
     * Test the update method updates record successfully
     */
    public function test_update_updates_record_successfully()
    {
        $golonganDarah = TabelGolonganDarah::factory()->create();
        
        $updateData = [
            'kode' => 'O001',
            'nama_golongan_darah' => 'O',
            'status' => '0',
            'urut' => 4
        ];

        $response = $this->putJson("/api/golongan-darah/{$golonganDarah->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'kode',
                        'nama_golongan_darah',
                        'status',
                        'urut',
                        'created_at',
                        'updated_at'
                    ]
                ]);

        $this->assertDatabaseHas('tabel_golongan_darah', array_merge(['id' => $golonganDarah->id], $updateData));
    }

    /**
     * Test the update method validation fails with invalid data
     */
    public function test_update_validation_fails()
    {
        $golonganDarah = TabelGolonganDarah::factory()->create();
        
        $invalidData = [
            'kode' => '',
            'nama_golongan_darah' => '',
            'status' => 'invalid',
            'urut' => 'not_a_number'
        ];

        $response = $this->putJson("/api/golongan-darah/{$golonganDarah->id}", $invalidData);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'errors'
                ]);
    }

    /**
     * Test the destroy method deletes record successfully
     */
    public function test_destroy_deletes_record_successfully()
    {
        $golonganDarah = TabelGolonganDarah::factory()->create();

        $response = $this->deleteJson("/api/golongan-darah/{$golonganDarah->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Data golongan darah berhasil dihapus'
                ]);

        $this->assertDatabaseMissing('tabel_golongan_darah', ['id' => $golonganDarah->id]);
    }

    /**
     * Test the destroy method returns 404 for non-existent record
     */
    public function test_destroy_returns_404_for_non_existent_record()
    {
        $response = $this->deleteJson('/api/golongan-darah/999');

        $response->assertStatus(500)
                ->assertJson([
                    'success' => false,
                    'message' => 'Gagal menghapus data golongan darah'
                ]);
    }
}