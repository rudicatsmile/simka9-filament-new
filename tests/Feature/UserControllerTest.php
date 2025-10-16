<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Test untuk UserController
 * Mengikuti pola test controller lain (response JSON konsisten).
 */
class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test index returns paginated data
     */
    public function test_index_returns_paginated_data()
    {
        User::factory()->count(15)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'current_page',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'email_verified_at',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
            ]);
    }

    /**
     * Test index with search
     */
    public function test_index_with_search()
    {
        User::factory()->create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
        ]);
        User::factory()->create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
        ]);

        $response = $this->getJson('/api/users?search=Alice');

        $response->assertStatus(200);
        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertEquals('Alice', $data[0]['name']);
    }

    /**
     * Test store creates record successfully
     */
    public function test_store_creates_record_successfully()
    {
        $data = [
            'name' => 'Charlie',
            'email' => 'charlie@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Data user berhasil disimpan',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'charlie@example.com',
            'name' => 'Charlie',
        ]);
    }

    /**
     * Test store validation fails
     */
    public function test_store_validation_fails()
    {
        $data = [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => [
                    'name',
                    'email',
                    'password',
                ],
            ]);
    }

    /**
     * Test show returns specific record
     */
    public function test_show_returns_specific_record()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ],
            ]);
    }

    /**
     * Test show returns 404 for non-existent record
     */
    public function test_show_returns_404_for_non_existent_record()
    {
        $response = $this->getJson('/api/users/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Data user tidak ditemukan',
            ]);
    }

    /**
     * Test update updates record successfully
     */
    public function test_update_updates_record_successfully()
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    /**
     * Test update validation fails
     */
    public function test_update_validation_fails()
    {
        $user = User::factory()->create();

        $invalidData = [
            'name' => '',
            'email' => 'invalid',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $invalidData);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors',
            ]);
    }

    /**
     * Test destroy deletes record successfully
     */
    public function test_destroy_deletes_record_successfully()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Data user berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /**
     * Test destroy returns 500 for non-existent record (mengikuti pola controller lain)
     */
    public function test_destroy_returns_500_for_non_existent_record()
    {
        $response = $this->deleteJson('/api/users/999');

        $response->assertStatus(500)
            ->assertJson([
                'success' => false,
                'message' => 'Gagal menghapus data user',
            ]);
    }
}