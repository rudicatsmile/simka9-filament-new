<?php

namespace Tests\Feature;

use App\Models\DataRiwayatPasangan;
use App\Models\DataPegawai;
use App\Models\JenjangPendidikan;
use App\Models\TabelPekerjaan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Test class untuk DataRiwayatPasanganController
 * 
 * @package Tests\Feature
 */
class DataRiwayatPasanganControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create required dependencies
        $this->dataPegawai = DataPegawai::factory()->create();
        $this->jenjangPendidikan = JenjangPendidikan::factory()->create();
        $this->tabelPekerjaan = TabelPekerjaan::factory()->create();
    }

    /**
     * Test untuk mengambil daftar data riwayat pasangan
     */
    public function test_can_get_data_riwayat_pasangan_list(): void
    {
        // Arrange
        DataRiwayatPasangan::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/data-riwayat-pasangan');

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
                                'nama_pasangan',
                                'tempat_lahir',
                                'tanggal_lahir',
                                'hubungan',
                                'kode_jenjang_pendidikan',
                                'kode_tabel_pekerjaan',
                                'urut',
                                'created_at',
                                'updated_at',
                                'data_pegawai',
                                'jenjang_pendidikan',
                                'tabel_pekerjaan'
                            ]
                        ]
                    ]
                ]);
    }

    /**
     * Test untuk membuat data riwayat pasangan baru
     */
    public function test_can_create_data_riwayat_pasangan(): void
    {
        // Arrange
        $data = [
            'nik_data_pegawai' => $this->dataPegawai->nik,
            'nama_pasangan' => 'Siti Nurhaliza',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1985-05-15',
            'hubungan' => 'Istri',
            'kode_jenjang_pendidikan' => $this->jenjangPendidikan->kode,
            'kode_tabel_pekerjaan' => $this->tabelPekerjaan->kode,
            'urut' => 1
        ];

        // Act
        $response = $this->postJson('/api/data-riwayat-pasangan', $data);

        // Assert
        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Data riwayat pasangan berhasil dibuat'
                ]);

        $this->assertDatabaseHas('data_riwayat_pasangan', $data);
    }

    /**
     * Test untuk validasi data saat membuat data riwayat pasangan
     */
    public function test_validation_when_creating_data_riwayat_pasangan(): void
    {
        // Act
        $response = $this->postJson('/api/data-riwayat-pasangan', []);

        // Assert
        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'nik_data_pegawai',
                    'nama_pasangan',
                    'tempat_lahir',
                    'tanggal_lahir',
                    'hubungan',
                    'urut'
                ]);
    }

    /**
     * Test untuk validasi hubungan yang tidak valid
     */
    public function test_validation_invalid_hubungan(): void
    {
        // Arrange
        $data = [
            'nik_data_pegawai' => $this->dataPegawai->nik,
            'nama_pasangan' => 'Test Name',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1985-05-15',
            'hubungan' => 'Invalid',
            'urut' => 1
        ];

        // Act
        $response = $this->postJson('/api/data-riwayat-pasangan', $data);

        // Assert
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['hubungan']);
    }

    /**
     * Test untuk mengambil detail data riwayat pasangan
     */
    public function test_can_show_data_riwayat_pasangan(): void
    {
        // Arrange
        $dataRiwayatPasangan = DataRiwayatPasangan::factory()->create();

        // Act
        $response = $this->getJson("/api/data-riwayat-pasangan/{$dataRiwayatPasangan->id}");

        // Assert
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Data riwayat pasangan berhasil diambil',
                    'data' => [
                        'id' => $dataRiwayatPasangan->id,
                        'nik_data_pegawai' => $dataRiwayatPasangan->nik_data_pegawai,
                        'nama_pasangan' => $dataRiwayatPasangan->nama_pasangan,
                        'hubungan' => $dataRiwayatPasangan->hubungan
                    ]
                ]);
    }

    /**
     * Test untuk mengupdate data riwayat pasangan
     */
    public function test_can_update_data_riwayat_pasangan(): void
    {
        // Arrange
        $dataRiwayatPasangan = DataRiwayatPasangan::factory()->create();
        $updateData = [
            'nik_data_pegawai' => $this->dataPegawai->nik,
            'nama_pasangan' => 'Updated Name',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1990-12-25',
            'hubungan' => 'Suami',
            'kode_jenjang_pendidikan' => $this->jenjangPendidikan->kode,
            'kode_tabel_pekerjaan' => $this->tabelPekerjaan->kode,
            'urut' => 2
        ];

        // Act
        $response = $this->putJson("/api/data-riwayat-pasangan/{$dataRiwayatPasangan->id}", $updateData);

        // Assert
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Data riwayat pasangan berhasil diperbarui'
                ]);

        $this->assertDatabaseHas('data_riwayat_pasangan', array_merge(['id' => $dataRiwayatPasangan->id], $updateData));
    }

    /**
     * Test untuk menghapus data riwayat pasangan
     */
    public function test_can_delete_data_riwayat_pasangan(): void
    {
        // Arrange
        $dataRiwayatPasangan = DataRiwayatPasangan::factory()->create();

        // Act
        $response = $this->deleteJson("/api/data-riwayat-pasangan/{$dataRiwayatPasangan->id}");

        // Assert
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Data riwayat pasangan berhasil dihapus'
                ]);

        $this->assertDatabaseMissing('data_riwayat_pasangan', ['id' => $dataRiwayatPasangan->id]);
    }

    /**
     * Test untuk mencari data riwayat pasangan yang tidak ada
     */
    public function test_show_nonexistent_data_riwayat_pasangan(): void
    {
        // Act
        $response = $this->getJson('/api/data-riwayat-pasangan/999');

        // Assert
        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Data riwayat pasangan tidak ditemukan'
                ]);
    }

    /**
     * Test untuk update data riwayat pasangan yang tidak ada
     */
    public function test_update_nonexistent_data_riwayat_pasangan(): void
    {
        // Arrange
        $updateData = [
            'nik_data_pegawai' => $this->dataPegawai->nik,
            'nama_pasangan' => 'Test Name',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1985-05-15',
            'hubungan' => 'Istri',
            'urut' => 1
        ];

        // Act
        $response = $this->putJson('/api/data-riwayat-pasangan/999', $updateData);

        // Assert
        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Data riwayat pasangan tidak ditemukan'
                ]);
    }

    /**
     * Test untuk delete data riwayat pasangan yang tidak ada
     */
    public function test_delete_nonexistent_data_riwayat_pasangan(): void
    {
        // Act
        $response = $this->deleteJson('/api/data-riwayat-pasangan/999');

        // Assert
        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Data riwayat pasangan tidak ditemukan'
                ]);
    }

    /**
     * Test untuk mengambil data riwayat pasangan berdasarkan NIK pegawai
     */
    public function test_can_get_data_riwayat_pasangan_by_employee(): void
    {
        // Arrange
        $dataPegawai = DataPegawai::factory()->create();
        DataRiwayatPasangan::factory()->count(2)->create([
            'nik_data_pegawai' => $dataPegawai->nik
        ]);

        // Act
        $response = $this->getJson("/api/data-riwayat-pasangan/employee/{$dataPegawai->nik}");

        // Assert
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Data riwayat pasangan pegawai berhasil diambil'
                ])
                ->assertJsonCount(2, 'data');
    }

    /**
     * Test untuk pencarian data riwayat pasangan
     */
    public function test_can_search_data_riwayat_pasangan(): void
    {
        // Arrange
        DataRiwayatPasangan::factory()->create([
            'nama_pasangan' => 'Siti Nurhaliza'
        ]);
        DataRiwayatPasangan::factory()->create([
            'nama_pasangan' => 'Ahmad Dhani'
        ]);

        // Act
        $response = $this->getJson('/api/data-riwayat-pasangan?search=Siti');

        // Assert
        $response->assertStatus(200)
                ->assertJsonCount(1, 'data.data');
    }

    /**
     * Test untuk filter berdasarkan NIK pegawai
     */
    public function test_can_filter_by_nik_pegawai(): void
    {
        // Arrange
        $dataPegawai1 = DataPegawai::factory()->create();
        $dataPegawai2 = DataPegawai::factory()->create();
        
        DataRiwayatPasangan::factory()->create([
            'nik_data_pegawai' => $dataPegawai1->nik
        ]);
        DataRiwayatPasangan::factory()->create([
            'nik_data_pegawai' => $dataPegawai2->nik
        ]);

        // Act
        $response = $this->getJson("/api/data-riwayat-pasangan?nik_pegawai={$dataPegawai1->nik}");

        // Assert
        $response->assertStatus(200)
                ->assertJsonCount(1, 'data.data');
    }

    /**
     * Test untuk validasi tanggal lahir tidak boleh di masa depan
     */
    public function test_validation_tanggal_lahir_future_date(): void
    {
        // Arrange
        $futureDate = now()->addYear()->format('Y-m-d');
        $data = [
            'nik_data_pegawai' => $this->dataPegawai->nik,
            'nama_pasangan' => 'Test Name',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => $futureDate,
            'hubungan' => 'Istri',
            'urut' => 1
        ];

        // Act
        $response = $this->postJson('/api/data-riwayat-pasangan', $data);

        // Assert
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['tanggal_lahir']);
    }

    /**
     * Test untuk mencegah duplikasi data
     */
    public function test_prevent_duplicate_data_riwayat_pasangan(): void
    {
        // Arrange
        $dataPegawai = DataPegawai::factory()->create();
        $existingData = DataRiwayatPasangan::factory()->create([
            'nik_data_pegawai' => $dataPegawai->nik
        ]);
        
        $duplicateData = [
            'nik_data_pegawai' => $existingData->nik_data_pegawai,
            'nama_pasangan' => $existingData->nama_pasangan,
            'tempat_lahir' => 'Different Place',
            'tanggal_lahir' => $existingData->tanggal_lahir->format('Y-m-d'),
            'hubungan' => $existingData->hubungan,
            'urut' => 2
        ];

        // Act
        $response = $this->postJson('/api/data-riwayat-pasangan', $duplicateData);

        // Assert
        $response->assertStatus(422)
                ->assertJson([
                    'success' => false,
                    'message' => 'Data riwayat pasangan dengan kombinasi yang sama sudah ada'
                ]);
    }
}