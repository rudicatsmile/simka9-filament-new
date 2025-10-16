<?php

namespace Tests\Feature;

use App\Models\TabelStatusNikah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Test class for StatusNikahController
 * 
 * This class contains unit tests for all CRUD operations
 * of the StatusNikahController including validation tests.
 */
class StatusNikahControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test the index method returns paginated data
     */
    public function test_index_returns_paginated_data()
    {
        // Create test data
        TabelStatusNikah::factory()->count(15)->create();

        $response = $this->getJson('/api/status-nikah');

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
                                'nama_status_nikah',
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
        TabelStatusNikah::factory()->create([
            'nama_status_nikah' => 'Belum Menikah',
            'kode' => 'BM001'
        ]);
        
        TabelStatusNikah::factory()->create([
            'nama_status_nikah' => 'Menikah',
            'kode' => 'M001'
        ]);

        $response = $this->getJson('/api/status-nikah?search=Belum');

        $response->assertStatus(200);
        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertEquals('Belum Menikah', $data[0]['nama_status_nikah']);
    }

    /**
     * Test the store method creates new record successfully
     */
    public function test_store_creates_record_successfully()
    {
        $data = [
            'kode' => 'CH001',
            'nama_status_nikah' => 'Cerai Hidup',
            'status' => '1',
            'urut' => 3
        ];

        $response = $this->postJson('/api/status-nikah', $data);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'kode',
                        'nama_status_nikah',
                        'status',
                        'urut',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'success' => true,
                    'message' => 'Data status nikah berhasil disimpan'
                ]);

        $this->assertDatabaseHas('tabel_status_nikahs', $data);
    }

    /**
     * Test the store method validation fails with invalid data
     */
    public function test_store_validation_fails()
    {
        $data = [
            'kode' => '', // Required field empty
            'nama_status_nikah' => '',
            'status' => 'invalid', // Invalid enum value
            'urut' => 'not_a_number' // Invalid integer
        ];

        $response = $this->postJson('/api/status-nikah', $data);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'errors' => [
                        'kode',
                        'nama_status_nikah',
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
        $statusNikah = TabelStatusNikah::factory()->create();

        $response = $this->getJson("/api/status-nikah/{$statusNikah->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'kode',
                        'nama_status_nikah',
                        'status',
                        'urut',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'data' => [
                        'id' => $statusNikah->id,
                        'kode' => $statusNikah->kode,
                        'nama_status_nikah' => $statusNikah->nama_status_nikah,
                        'status' => $statusNikah->status,
                        'urut' => $statusNikah->urut
                    ]
                ]);
    }

    /**
     * Test the show method returns 404 for non-existent record
     */
    public function test_show_returns_404_for_non_existent_record()
    {
        $response = $this->getJson('/api/status-nikah/999');

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Data status nikah tidak ditemukan'
                ]);
    }

    /**
     * Test the update method updates record successfully
     */
    public function test_update_updates_record_successfully()
    {
        $statusNikah = TabelStatusNikah::factory()->create();
        
        $updateData = [
            'kode' => 'CM001',
            'nama_status_nikah' => 'Cerai Mati',
            'status' => '0',
            'urut' => 4
        ];

        $response = $this->putJson("/api/status-nikah/{$statusNikah->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'kode',
                        'nama_status_nikah',
                        'status',
                        'urut',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'success' => true,
                    'message' => 'Data status nikah berhasil diperbarui'
                ]);

        $this->assertDatabaseHas('tabel_status_nikahs', array_merge(['id' => $statusNikah->id], $updateData));
    }

    /**
     * Test the update method validation fails with invalid data
     */
    public function test_update_validation_fails()
    {
        $statusNikah = TabelStatusNikah::factory()->create();
        
        $invalidData = [
            'kode' => '',
            'nama_status_nikah' => '',
            'status' => 'invalid',
            'urut' => 'not_a_number'
        ];

        $response = $this->putJson("/api/status-nikah/{$statusNikah->id}", $invalidData);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'errors' => [
                        'kode',
                        'nama_status_nikah',
                        'status',
                        'urut'
                    ]
                ]);
    }

    /**
     * Test the destroy method deletes record successfully
     */
    public function test_destroy_deletes_record_successfully()
    {
        $statusNikah = TabelStatusNikah::factory()->create();

        $response = $this->deleteJson("/api/status-nikah/{$statusNikah->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Data status nikah berhasil dihapus'
                ]);

        $this->assertDatabaseMissing('tabel_status_nikahs', ['id' => $statusNikah->id]);
    }

    /**
     * Test the destroy method returns 404 for non-existent record
     */
    public function test_destroy_returns_404_for_non_existent_record()
    {
        $response = $this->deleteJson('/api/status-nikah/999');

        $response->assertStatus(500)
                ->assertJson([
                    'success' => false
                ]);
    }
}
