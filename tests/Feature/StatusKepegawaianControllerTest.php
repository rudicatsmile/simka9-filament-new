<?php

namespace Tests\Feature;

use App\Models\TabelStatusKepegawaian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Test class untuk StatusKepegawaianController
 * 
 * @package Tests\Feature
 */
class StatusKepegawaianControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test untuk mengambil daftar status kepegawaian
     */
    public function test_can_get_status_kepegawaian_list(): void
    {
        // Arrange
        TabelStatusKepegawaian::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/status-kepegawaian');

        // Assert
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'kode',
                                'nama_status_kepegawaian',
                                'status',
                                'urut',
                                'created_at',
                                'updated_at'
                            ]
                        ]
                    ]
                ]);
    }

    /**
     * Test untuk membuat status kepegawaian baru
     */
    public function test_can_create_status_kepegawaian(): void
    {
        // Arrange
        $data = [
            'kode' => 'PNS',
            'nama_status_kepegawaian' => 'Pegawai Negeri Sipil',
            'status' => '1',
            'urut' => 1
        ];

        // Act
        $response = $this->postJson('/api/status-kepegawaian', $data);

        // Assert
        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Status kepegawaian berhasil dibuat'
                ]);

        $this->assertDatabaseHas('tabel_status_kepegawaians', $data);
    }

    /**
     * Test untuk validasi data saat membuat status kepegawaian
     */
    public function test_validation_when_creating_status_kepegawaian(): void
    {
        // Act
        $response = $this->postJson('/api/status-kepegawaian', []);

        // Assert
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['kode', 'nama_status_kepegawaian', 'status', 'urut']);
    }

    /**
     * Test untuk mengambil detail status kepegawaian
     */
    public function test_can_show_status_kepegawaian(): void
    {
        // Arrange
        $statusKepegawaian = TabelStatusKepegawaian::factory()->create();

        // Act
        $response = $this->getJson("/api/status-kepegawaian/{$statusKepegawaian->id}");

        // Assert
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Detail status kepegawaian berhasil diambil',
                    'data' => [
                        'id' => $statusKepegawaian->id,
                        'kode' => $statusKepegawaian->kode,
                        'nama_status_kepegawaian' => $statusKepegawaian->nama_status_kepegawaian
                    ]
                ]);
    }

    /**
     * Test untuk mengupdate status kepegawaian
     */
    public function test_can_update_status_kepegawaian(): void
    {
        // Arrange
        $statusKepegawaian = TabelStatusKepegawaian::factory()->create();
        $updateData = [
            'kode' => 'PPPK',
            'nama_status_kepegawaian' => 'Pegawai Pemerintah dengan Perjanjian Kerja',
            'status' => '1',
            'urut' => 2
        ];

        // Act
        $response = $this->putJson("/api/status-kepegawaian/{$statusKepegawaian->id}", $updateData);

        // Assert
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Status kepegawaian berhasil diperbarui'
                ]);

        $this->assertDatabaseHas('tabel_status_kepegawaians', array_merge(['id' => $statusKepegawaian->id], $updateData));
    }

    /**
     * Test untuk menghapus status kepegawaian
     */
    public function test_can_delete_status_kepegawaian(): void
    {
        // Arrange
        $statusKepegawaian = TabelStatusKepegawaian::factory()->create();

        // Act
        $response = $this->deleteJson("/api/status-kepegawaian/{$statusKepegawaian->id}");

        // Assert
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Status kepegawaian berhasil dihapus'
                ]);

        $this->assertDatabaseMissing('tabel_status_kepegawaians', ['id' => $statusKepegawaian->id]);
    }

    /**
     * Test untuk mencari status kepegawaian yang tidak ada
     */
    public function test_show_nonexistent_status_kepegawaian(): void
    {
        // Act
        $response = $this->getJson('/api/status-kepegawaian/999');

        // Assert
        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Status kepegawaian tidak ditemukan'
                ]);
    }

    /**
     * Test untuk update status kepegawaian yang tidak ada
     */
    public function test_update_nonexistent_status_kepegawaian(): void
    {
        // Act
        $response = $this->putJson('/api/status-kepegawaian/999', [
            'kode' => 'TEST',
            'nama_status_kepegawaian' => 'Test Status',
            'status' => '1',
            'urut' => 1
        ]);

        // Assert
        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Status kepegawaian tidak ditemukan'
                ]);
    }

    /**
     * Test untuk delete status kepegawaian yang tidak ada
     */
    public function test_delete_nonexistent_status_kepegawaian(): void
    {
        // Act
        $response = $this->deleteJson('/api/status-kepegawaian/999');

        // Assert
        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Status kepegawaian tidak ditemukan'
                ]);
    }

    /**
     * Test untuk validasi unique kode saat membuat status kepegawaian
     */
    public function test_unique_kode_validation_when_creating(): void
    {
        // Arrange
        $existingStatus = TabelStatusKepegawaian::factory()->create(['kode' => 'PNS']);

        // Act
        $response = $this->postJson('/api/status-kepegawaian', [
            'kode' => 'PNS',
            'nama_status_kepegawaian' => 'Test Status',
            'status' => '1',
            'urut' => 1
        ]);

        // Assert
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['kode']);
    }
}
