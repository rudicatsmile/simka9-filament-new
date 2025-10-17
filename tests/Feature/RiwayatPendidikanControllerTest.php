<?php

namespace Tests\Feature;

use App\Models\RiwayatPendidikan;
use App\Models\DataPegawai;
use App\Models\JenjangPendidikan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Test class untuk RiwayatPendidikanController
 * 
 * @package Tests\Feature
 */
class RiwayatPendidikanControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create required dependencies
        $this->dataPegawai = DataPegawai::factory()->create();
        $this->jenjangPendidikan = JenjangPendidikan::factory()->create();
    }

    /**
     * Test untuk mengambil daftar riwayat pendidikan
     */
    public function test_can_get_riwayat_pendidikan_list(): void
    {
        // Arrange
        RiwayatPendidikan::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/riwayat-pendidikan');

        // Assert
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'nik_data_pegawai',
                                'kode_jenjang_pendidikan',
                                'nama_sekolah',
                                'tahun_ijazah',
                                'urut',
                                'created_at',
                                'updated_at',
                                'data_pegawai',
                                'jenjang_pendidikan'
                            ]
                        ]
                    ]
                ]);
    }

    /**
     * Test untuk membuat riwayat pendidikan baru
     */
    public function test_can_create_riwayat_pendidikan(): void
    {
        // Arrange
        $data = [
            'nik_data_pegawai' => $this->dataPegawai->nik,
            'kode_jenjang_pendidikan' => $this->jenjangPendidikan->kode,
            'nama_sekolah' => 'Universitas Indonesia',
            'tahun_ijazah' => 2020,
            'urut' => 1
        ];

        // Act
        $response = $this->postJson('/api/riwayat-pendidikan', $data);

        // Assert
        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Riwayat pendidikan berhasil dibuat'
                ]);

        $this->assertDatabaseHas('data_riwayat_pendidikan', $data);
    }

    /**
     * Test untuk validasi data saat membuat riwayat pendidikan
     */
    public function test_validation_when_creating_riwayat_pendidikan(): void
    {
        // Act
        $response = $this->postJson('/api/riwayat-pendidikan', []);

        // Assert
        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'nik_data_pegawai',
                    'kode_jenjang_pendidikan',
                    'nama_sekolah',
                    'tahun_ijazah',
                    'urut'
                ]);
    }

    /**
     * Test untuk mengambil detail riwayat pendidikan
     */
    public function test_can_show_riwayat_pendidikan(): void
    {
        // Arrange
        $riwayatPendidikan = RiwayatPendidikan::factory()->create();

        // Act
        $response = $this->getJson("/api/riwayat-pendidikan/{$riwayatPendidikan->id}");

        // Assert
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Detail riwayat pendidikan berhasil diambil',
                    'data' => [
                        'id' => $riwayatPendidikan->id,
                        'nik_data_pegawai' => $riwayatPendidikan->nik_data_pegawai,
                        'kode_jenjang_pendidikan' => $riwayatPendidikan->kode_jenjang_pendidikan,
                        'nama_sekolah' => $riwayatPendidikan->nama_sekolah
                    ]
                ]);
    }

    /**
     * Test untuk mengupdate riwayat pendidikan
     */
    public function test_can_update_riwayat_pendidikan(): void
    {
        // Arrange
        $riwayatPendidikan = RiwayatPendidikan::factory()->create();
        $updateData = [
            'nik_data_pegawai' => $this->dataPegawai->nik,
            'kode_jenjang_pendidikan' => $this->jenjangPendidikan->kode,
            'nama_sekolah' => 'Institut Teknologi Bandung',
            'tahun_ijazah' => 2021,
            'urut' => 2
        ];

        // Act
        $response = $this->putJson("/api/riwayat-pendidikan/{$riwayatPendidikan->id}", $updateData);

        // Assert
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Data riwayat pendidikan berhasil diperbarui'
                ]);

        $this->assertDatabaseHas('data_riwayat_pendidikan', array_merge(['id' => $riwayatPendidikan->id], $updateData));
    }

    /**
     * Test untuk menghapus riwayat pendidikan
     */
    public function test_can_delete_riwayat_pendidikan(): void
    {
        // Arrange
        $riwayatPendidikan = RiwayatPendidikan::factory()->create();

        // Act
        $response = $this->deleteJson("/api/riwayat-pendidikan/{$riwayatPendidikan->id}");

        // Assert
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Data riwayat pendidikan berhasil dihapus'
                ]);

        $this->assertDatabaseMissing('data_riwayat_pendidikan', ['id' => $riwayatPendidikan->id]);
    }

    /**
     * Test untuk mencari riwayat pendidikan yang tidak ada
     */
    public function test_show_nonexistent_riwayat_pendidikan(): void
    {
        // Act
        $response = $this->getJson('/api/riwayat-pendidikan/999');

        // Assert
        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Data riwayat pendidikan tidak ditemukan'
                ]);
    }

    /**
     * Test untuk update riwayat pendidikan yang tidak ada
     */
    public function test_update_nonexistent_riwayat_pendidikan(): void
    {
        // Act
        $response = $this->putJson('/api/riwayat-pendidikan/999', [
            'nik_data_pegawai' => $this->dataPegawai->nik,
            'kode_jenjang_pendidikan' => $this->jenjangPendidikan->kode,
            'nama_sekolah' => 'Test University',
            'tahun_ijazah' => 2020,
            'urut' => 1
        ]);

        // Assert
        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Data riwayat pendidikan tidak ditemukan'
                ]);
    }

    /**
     * Test untuk delete riwayat pendidikan yang tidak ada
     */
    public function test_delete_nonexistent_riwayat_pendidikan(): void
    {
        // Act
        $response = $this->deleteJson('/api/riwayat-pendidikan/999');

        // Assert
        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Data riwayat pendidikan tidak ditemukan'
                ]);
    }

    /**
     * Test untuk pencarian riwayat pendidikan
     */
    public function test_can_search_riwayat_pendidikan(): void
    {
        // Arrange
        RiwayatPendidikan::factory()->create([
            'nama_sekolah' => 'Universitas Indonesia'
        ]);
        RiwayatPendidikan::factory()->create([
            'nama_sekolah' => 'Institut Teknologi Bandung'
        ]);

        // Act
        $response = $this->getJson('/api/riwayat-pendidikan?search=Indonesia');

        // Assert
        $response->assertStatus(200);
        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertStringContainsString('Indonesia', $data[0]['nama_sekolah']);
    }

    /**
     * Test untuk filter berdasarkan pegawai
     */
    public function test_can_filter_by_employee(): void
    {
        // Arrange
        $pegawai1 = DataPegawai::factory()->create();
        $pegawai2 = DataPegawai::factory()->create();
        
        RiwayatPendidikan::factory()->create(['nik_data_pegawai' => $pegawai1->nik]);
        RiwayatPendidikan::factory()->create(['nik_data_pegawai' => $pegawai2->nik]);

        // Act
        $response = $this->getJson("/api/riwayat-pendidikan?nik_data_pegawai={$pegawai1->nik}");

        // Assert
        $response->assertStatus(200);
        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertEquals($pegawai1->nik, $data[0]['nik_data_pegawai']);
    }

    /**
     * Test untuk mengambil riwayat pendidikan berdasarkan pegawai
     */
    public function test_can_get_riwayat_pendidikan_by_employee(): void
    {
        // Arrange
        $pegawai = DataPegawai::factory()->create();
        RiwayatPendidikan::factory()->count(2)->create([
            'nik_data_pegawai' => $pegawai->nik
        ]);

        // Act
        $response = $this->getJson("/api/riwayat-pendidikan/employee/{$pegawai->nik}");

        // Assert
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Riwayat pendidikan pegawai berhasil diambil'
                ]);
        
        $data = $response->json('data');
        $this->assertCount(2, $data);
    }

    /**
     * Test untuk validasi duplikasi data
     */
    public function test_validation_duplicate_riwayat_pendidikan(): void
    {
        // Arrange
        $pegawai = DataPegawai::factory()->create();
        $jenjang = JenjangPendidikan::factory()->create();
        
        $existingData = RiwayatPendidikan::factory()->create([
            'nik_data_pegawai' => $pegawai->nik,
            'kode_jenjang_pendidikan' => $jenjang->kode,
            'nama_sekolah' => 'Universitas Test',
            'tahun_ijazah' => 2020,
            'urut' => 1
        ]);
        
        $duplicateData = [
            'nik_data_pegawai' => $existingData->nik_data_pegawai,
            'kode_jenjang_pendidikan' => $existingData->kode_jenjang_pendidikan,
            'nama_sekolah' => $existingData->nama_sekolah,
            'tahun_ijazah' => $existingData->tahun_ijazah,
            'urut' => $existingData->urut
        ];

        // Act
        $response = $this->postJson('/api/riwayat-pendidikan', $duplicateData);

        // Assert
        $response->assertStatus(422)
                ->assertJson([
                    'success' => false,
                    'message' => 'Data riwayat pendidikan dengan kombinasi yang sama sudah ada'
                ]);
    }
}