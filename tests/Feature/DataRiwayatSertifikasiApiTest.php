<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\DataRiwayatSertifikasi;
use App\Models\DataPegawai;
use App\Models\CwspsRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * DataRiwayatSertifikasiApiTest
 * 
 * Feature tests untuk API DataRiwayatSertifikasi
 * Menguji endpoint API, authentication, authorization, dan file operations
 * 
 * @package Tests\Feature
 * @author SIMKA9 Development Team
 * @version 1.0.0
 */
class DataRiwayatSertifikasiApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $operator;
    protected User $viewer;
    protected User $regularUser;
    protected DataPegawai $pegawai;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed database
        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);

        // Get roles
        $adminRole = CwspsRole::where('identifier', 'admin')->first();
        $operatorRole = CwspsRole::where('identifier', 'operator')->first();
        $viewerRole = CwspsRole::where('identifier', 'viewer')->first();

        // Create test users with different roles
        $this->admin = User::factory()->create();
        if ($adminRole) {
            $this->admin->assignRole($adminRole);
        }

        $this->operator = User::factory()->create();
        if ($operatorRole) {
            $this->operator->assignRole($operatorRole);
        }

        $this->viewer = User::factory()->create();
        if ($viewerRole) {
            $this->viewer->assignRole($viewerRole);
        }

        $this->regularUser = User::factory()->create();
        // No role assigned

        // Create test pegawai
        $this->pegawai = DataPegawai::factory()->create();

        // Setup fake storage
        Storage::fake('public');
    }

    /**
     * Test unauthenticated access is denied
     */
    public function test_unauthenticated_access_denied(): void
    {
        $response = $this->getJson('/api/data-riwayat-sertifikasi');
        $response->assertStatus(401);
    }

    /**
     * Test unauthorized user cannot access API
     */
    public function test_unauthorized_user_cannot_access(): void
    {
        $response = $this->actingAs($this->regularUser, 'sanctum')
            ->getJson('/api/data-riwayat-sertifikasi');
        
        $response->assertStatus(403);
    }

    /**
     * Test admin can list all data riwayat sertifikasi
     */
    public function test_admin_can_list_data_riwayat_sertifikasi(): void
    {
        // Create test data
        DataRiwayatSertifikasi::factory()->count(3)->create([
            'nik_data_pegawai' => $this->pegawai->nik
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/data-riwayat-sertifikasi');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'nik_data_pegawai',
                        'is_sertifikasi',
                        'nama',
                        'nomor',
                        'tahun',
                        'induk_inpasing',
                        'sk_inpasing',
                        'tahun_inpasing',
                        'berkas',
                        'urut',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'links',
                'meta'
            ]);
    }

    /**
     * Test admin can create new data riwayat sertifikasi
     */
    public function test_admin_can_create_data_riwayat_sertifikasi(): void
    {
        $data = [
            'nik_data_pegawai' => $this->pegawai->nik,
            'is_sertifikasi' => true,
            'nama' => 'Sertifikat Test',
            'nomor' => 'CERT-001',
            'tahun' => 2024,
            'induk_inpasing' => 'INP-001',
            'sk_inpasing' => 'SK-001',
            'tahun_inpasing' => 2024
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/data-riwayat-sertifikasi', $data);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'nama' => 'Sertifikat Test',
                'nomor' => 'CERT-001'
            ]);

        $this->assertDatabaseHas('data_riwayat_sertifikasi', [
            'nama' => 'Sertifikat Test',
            'nomor' => 'CERT-001'
        ]);
    }

    /**
     * Test admin can view specific data riwayat sertifikasi
     */
    public function test_admin_can_view_data_riwayat_sertifikasi(): void
    {
        $riwayat = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/data-riwayat-sertifikasi/{$riwayat->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $riwayat->id,
                'nama' => $riwayat->nama
            ]);
    }

    /**
     * Test admin can update data riwayat sertifikasi
     */
    public function test_admin_can_update_data_riwayat_sertifikasi(): void
    {
        $riwayat = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik
        ]);

        $updateData = [
            'nama' => 'Updated Certificate Name',
            'nomor' => 'UPDATED-001'
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/data-riwayat-sertifikasi/{$riwayat->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'nama' => 'Updated Certificate Name',
                'nomor' => 'UPDATED-001'
            ]);

        $this->assertDatabaseHas('data_riwayat_sertifikasi', [
            'id' => $riwayat->id,
            'nama' => 'Updated Certificate Name'
        ]);
    }

    /**
     * Test admin can delete data riwayat sertifikasi
     */
    public function test_admin_can_delete_data_riwayat_sertifikasi(): void
    {
        $riwayat = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/data-riwayat-sertifikasi/{$riwayat->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('data_riwayat_sertifikasi', [
            'id' => $riwayat->id
        ]);
    }

    /**
     * Test file upload functionality
     */
    public function test_can_upload_file(): void
    {
        $file = UploadedFile::fake()->create('certificate.pdf', 1000, 'application/pdf');

        $data = [
            'nik_data_pegawai' => $this->pegawai->nik,
            'is_sertifikasi' => true,
            'nama' => 'Sertifikat dengan File',
            'nomor' => 'CERT-FILE-001',
            'tahun' => 2024,
            'berkas' => $file
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/data-riwayat-sertifikasi', $data);

        $response->assertStatus(201);

        $riwayat = DataRiwayatSertifikasi::where('nama', 'Sertifikat dengan File')->first();
        $this->assertNotNull($riwayat->berkas);
        Storage::disk('public')->assertExists($riwayat->berkas);
    }

    /**
     * Test file download functionality
     */
    public function test_can_download_file(): void
    {
        // Create a fake file
        $file = UploadedFile::fake()->create('certificate.pdf', 1000, 'application/pdf');
        $filePath = $file->store('sertifikasi', 'public');

        $riwayat = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik,
            'berkas' => $filePath
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->get("/api/data-riwayat-sertifikasi/{$riwayat->id}/download");

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /**
     * Test download non-existent file returns 404
     */
    public function test_download_non_existent_file_returns_404(): void
    {
        $riwayat = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik,
            'berkas' => null
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->get("/api/data-riwayat-sertifikasi/{$riwayat->id}/download");

        $response->assertStatus(404);
    }

    /**
     * Test viewer can only read data
     */
    public function test_viewer_can_only_read(): void
    {
        $riwayat = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik
        ]);

        // Can view list
        $response = $this->actingAs($this->viewer, 'sanctum')
            ->getJson('/api/data-riwayat-sertifikasi');
        $response->assertStatus(200);

        // Can view single item
        $response = $this->actingAs($this->viewer, 'sanctum')
            ->getJson("/api/data-riwayat-sertifikasi/{$riwayat->id}");
        $response->assertStatus(200);

        // Cannot create
        $response = $this->actingAs($this->viewer, 'sanctum')
            ->postJson('/api/data-riwayat-sertifikasi', [
                'nik_data_pegawai' => $this->pegawai->nik,
                'nama' => 'Test'
            ]);
        $response->assertStatus(403);

        // Cannot update
        $response = $this->actingAs($this->viewer, 'sanctum')
            ->putJson("/api/data-riwayat-sertifikasi/{$riwayat->id}", [
                'nama' => 'Updated'
            ]);
        $response->assertStatus(403);

        // Cannot delete
        $response = $this->actingAs($this->viewer, 'sanctum')
            ->deleteJson("/api/data-riwayat-sertifikasi/{$riwayat->id}");
        $response->assertStatus(403);
    }

    /**
     * Test search functionality
     */
    public function test_search_functionality(): void
    {
        DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik,
            'nama' => 'Sertifikat Komputer'
        ]);

        DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik,
            'nama' => 'Sertifikat Bahasa'
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/data-riwayat-sertifikasi?search=Komputer');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Sertifikat Komputer', $data[0]['nama']);
    }

    /**
     * Test filtering by employee
     */
    public function test_filter_by_employee(): void
    {
        $pegawai2 = DataPegawai::factory()->create();

        DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik,
            'nama' => 'Sertifikat Pegawai 1'
        ]);

        DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $pegawai2->nik,
            'nama' => 'Sertifikat Pegawai 2'
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/data-riwayat-sertifikasi?employee={$this->pegawai->nik}");

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals($this->pegawai->nik, $data[0]['nik_data_pegawai']);
    }

    /**
     * Test pagination
     */
    public function test_pagination(): void
    {
        DataRiwayatSertifikasi::factory()->count(25)->create([
            'nik_data_pegawai' => $this->pegawai->nik
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/data-riwayat-sertifikasi?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next'
                ],
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total'
                ]
            ]);

        $this->assertEquals(10, count($response->json('data')));
        $this->assertEquals(25, $response->json('meta.total'));
    }

    /**
     * Test sorting functionality
     */
    public function test_sorting(): void
    {
        DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik,
            'nama' => 'A Certificate',
            'tahun' => 2023
        ]);

        DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik,
            'nama' => 'B Certificate',
            'tahun' => 2024
        ]);

        // Sort by name ascending
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/data-riwayat-sertifikasi?sort=nama&direction=asc');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals('A Certificate', $data[0]['nama']);

        // Sort by year descending
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/data-riwayat-sertifikasi?sort=tahun&direction=desc');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals(2024, $data[0]['tahun']);
    }

    /**
     * Test validation errors
     */
    public function test_validation_errors(): void
    {
        // Test required fields
        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/data-riwayat-sertifikasi', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik_data_pegawai', 'nama']);

        // Test invalid file type
        $invalidFile = UploadedFile::fake()->create('document.txt', 1000, 'text/plain');

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/data-riwayat-sertifikasi', [
                'nik_data_pegawai' => $this->pegawai->nik,
                'nama' => 'Test Certificate',
                'berkas' => $invalidFile
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['berkas']);

        // Test file too large
        $largeFile = UploadedFile::fake()->create('large.pdf', 6000, 'application/pdf'); // 6MB

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/data-riwayat-sertifikasi', [
                'nik_data_pegawai' => $this->pegawai->nik,
                'nama' => 'Test Certificate',
                'berkas' => $largeFile
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['berkas']);
    }

    /**
     * Test 404 for non-existent resource
     */
    public function test_404_for_non_existent_resource(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/data-riwayat-sertifikasi/99999');

        $response->assertStatus(404);
    }
}