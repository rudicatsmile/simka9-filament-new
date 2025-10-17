<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\DataRiwayatSertifikasi;
use App\Models\DataPegawai;
use App\Models\CwspsRole;
use App\Filament\Resources\DataRiwayatSertifikasiResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

/**
 * DataRiwayatSertifikasiFilamentTest
 * 
 * Feature tests untuk Filament Resource DataRiwayatSertifikasi
 * Menguji CRUD operations, file upload/download, dan authorization melalui Filament interface
 * 
 * @package Tests\Feature
 * @author SIMKA9 Development Team
 * @version 1.0.0
 */
class DataRiwayatSertifikasiFilamentTest extends TestCase
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
     * Test unauthenticated user cannot access Filament resource
     */
    public function test_unauthenticated_user_cannot_access(): void
    {
        $response = $this->get(DataRiwayatSertifikasiResource::getUrl('index'));
        
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test regular user without role cannot access Filament resource
     */
    public function test_regular_user_cannot_access(): void
    {
        $this->actingAs($this->regularUser);
        
        $response = $this->get(DataRiwayatSertifikasiResource::getUrl('index'));
        
        $response->assertStatus(403);
    }

    /**
     * Test admin can access index page
     */
    public function test_admin_can_access_index(): void
    {
        $this->actingAs($this->admin);
        
        $response = $this->get(DataRiwayatSertifikasiResource::getUrl('index'));
        
        $response->assertStatus(200);
    }

    /**
     * Test operator can access index page
     */
    public function test_operator_can_access_index(): void
    {
        $this->actingAs($this->operator);
        
        $response = $this->get(DataRiwayatSertifikasiResource::getUrl('index'));
        
        $response->assertStatus(200);
    }

    /**
     * Test viewer can access index page
     */
    public function test_viewer_can_access_index(): void
    {
        $this->actingAs($this->viewer);
        
        $response = $this->get(DataRiwayatSertifikasiResource::getUrl('index'));
        
        $response->assertStatus(200);
    }

    /**
     * Test admin can access create page
     */
    public function test_admin_can_access_create(): void
    {
        $this->actingAs($this->admin);
        
        $response = $this->get(DataRiwayatSertifikasiResource::getUrl('create'));
        
        $response->assertStatus(200);
    }

    /**
     * Test operator can access create page
     */
    public function test_operator_can_access_create(): void
    {
        $this->actingAs($this->operator);
        
        $response = $this->get(DataRiwayatSertifikasiResource::getUrl('create'));
        
        $response->assertStatus(200);
    }

    /**
     * Test viewer cannot access create page
     */
    public function test_viewer_cannot_access_create(): void
    {
        $this->actingAs($this->viewer);
        
        $response = $this->get(DataRiwayatSertifikasiResource::getUrl('create'));
        
        $response->assertStatus(403);
    }

    /**
     * Test admin can create new record
     */
    public function test_admin_can_create_record(): void
    {
        $this->actingAs($this->admin);

        $data = [
            'nik_data_pegawai' => $this->pegawai->nik,
            'is_sertifikasi' => true,
            'nama' => 'Sertifikat Test Filament',
            'nomor' => 'CERT-FILAMENT-001',
            'tahun' => 2024,
            'induk_inpasing' => 'INP-001',
            'sk_inpasing' => 'SK-001',
            'tahun_inpasing' => 2024
        ];

        Livewire::test(DataRiwayatSertifikasiResource\Pages\CreateDataRiwayatSertifikasi::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('data_riwayat_sertifikasi', [
            'nama' => 'Sertifikat Test Filament',
            'nomor' => 'CERT-FILAMENT-001'
        ]);
    }

    /**
     * Test admin can view record
     */
    public function test_admin_can_view_record(): void
    {
        $this->actingAs($this->admin);

        $riwayat = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik
        ]);

        $response = $this->get(DataRiwayatSertifikasiResource::getUrl('view', ['record' => $riwayat]));
        
        $response->assertStatus(200);
    }

    /**
     * Test admin can access edit page
     */
    public function test_admin_can_access_edit(): void
    {
        $this->actingAs($this->admin);

        $riwayat = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik
        ]);

        $response = $this->get(DataRiwayatSertifikasiResource::getUrl('edit', ['record' => $riwayat]));
        
        $response->assertStatus(200);
    }

    /**
     * Test viewer cannot access edit page
     */
    public function test_viewer_cannot_access_edit(): void
    {
        $this->actingAs($this->viewer);

        $riwayat = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik
        ]);

        $response = $this->get(DataRiwayatSertifikasiResource::getUrl('edit', ['record' => $riwayat]));
        
        $response->assertStatus(403);
    }

    /**
     * Test admin can update record
     */
    public function test_admin_can_update_record(): void
    {
        $this->actingAs($this->admin);

        $riwayat = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik
        ]);

        $updateData = [
            'nama' => 'Updated Certificate Name',
            'nomor' => 'UPDATED-CERT-001'
        ];

        Livewire::test(DataRiwayatSertifikasiResource\Pages\EditDataRiwayatSertifikasi::class, ['record' => $riwayat->getRouteKey()])
            ->fillForm($updateData)
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('data_riwayat_sertifikasi', [
            'id' => $riwayat->id,
            'nama' => 'Updated Certificate Name',
            'nomor' => 'UPDATED-CERT-001'
        ]);
    }

    /**
     * Test admin can delete record
     */
    public function test_admin_can_delete_record(): void
    {
        $this->actingAs($this->admin);

        $riwayat = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik
        ]);

        Livewire::test(DataRiwayatSertifikasiResource\Pages\ListDataRiwayatSertifikasis::class)
            ->callTableAction('delete', $riwayat);

        $this->assertDatabaseMissing('data_riwayat_sertifikasi', [
            'id' => $riwayat->id
        ]);
    }

    /**
     * Test viewer cannot delete record
     */
    public function test_viewer_cannot_delete_record(): void
    {
        $this->actingAs($this->viewer);

        $riwayat = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik
        ]);

        Livewire::test(DataRiwayatSertifikasiResource\Pages\ListDataRiwayatSertifikasis::class)
            ->assertTableActionHidden('delete', $riwayat);
    }

    /**
     * Test bulk delete functionality
     */
    public function test_admin_can_bulk_delete(): void
    {
        $this->actingAs($this->admin);

        $riwayats = DataRiwayatSertifikasi::factory()->count(3)->create([
            'nik_data_pegawai' => $this->pegawai->nik
        ]);

        Livewire::test(DataRiwayatSertifikasiResource\Pages\ListDataRiwayatSertifikasis::class)
            ->callTableBulkAction('delete', $riwayats);

        foreach ($riwayats as $riwayat) {
            $this->assertDatabaseMissing('data_riwayat_sertifikasi', [
                'id' => $riwayat->id
            ]);
        }
    }

    /**
     * Test form validation
     */
    public function test_form_validation(): void
    {
        $this->actingAs($this->admin);

        // Test required fields
        Livewire::test(DataRiwayatSertifikasiResource\Pages\CreateDataRiwayatSertifikasi::class)
            ->fillForm([
                'nik_data_pegawai' => '',
                'nama' => ''
            ])
            ->call('create')
            ->assertHasFormErrors(['nik_data_pegawai', 'nama']);
    }

    /**
     * Test file upload functionality
     */
    public function test_file_upload(): void
    {
        $this->actingAs($this->admin);

        $file = UploadedFile::fake()->create('certificate.pdf', 1000, 'application/pdf');

        $data = [
            'nik_data_pegawai' => $this->pegawai->nik,
            'is_sertifikasi' => true,
            'nama' => 'Sertifikat dengan File',
            'nomor' => 'CERT-FILE-001',
            'tahun' => 2024,
            'berkas' => $file
        ];

        Livewire::test(DataRiwayatSertifikasiResource\Pages\CreateDataRiwayatSertifikasi::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasNoFormErrors();

        $riwayat = DataRiwayatSertifikasi::where('nama', 'Sertifikat dengan File')->first();
        $this->assertNotNull($riwayat->berkas);
        Storage::disk('public')->assertExists($riwayat->berkas);
    }

    /**
     * Test download functionality
     */
    public function test_download_functionality(): void
    {
        $this->actingAs($this->admin);

        // Create a fake file
        $file = UploadedFile::fake()->create('certificate.pdf', 1000, 'application/pdf');
        $filePath = $file->store('sertifikasi', 'public');

        $riwayat = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik,
            'berkas' => $filePath
        ]);

        $response = $this->get(route('admin.data-riwayat-sertifikasis.download', $riwayat));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /**
     * Test table filters
     */
    public function test_table_filters(): void
    {
        $this->actingAs($this->admin);

        // Create test data with different statuses
        DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik,
            'is_sertifikasi' => true,
            'tahun' => 2023
        ]);

        DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik,
            'is_sertifikasi' => false,
            'tahun' => 2024
        ]);

        // Test certification status filter
        Livewire::test(DataRiwayatSertifikasiResource\Pages\ListDataRiwayatSertifikasis::class)
            ->filterTable('is_sertifikasi', true)
            ->assertCanSeeTableRecords(
                DataRiwayatSertifikasi::where('is_sertifikasi', true)->get()
            );

        // Test year filter
        Livewire::test(DataRiwayatSertifikasiResource\Pages\ListDataRiwayatSertifikasis::class)
            ->filterTable('tahun', 2023)
            ->assertCanSeeTableRecords(
                DataRiwayatSertifikasi::where('tahun', 2023)->get()
            );
    }

    /**
     * Test table search
     */
    public function test_table_search(): void
    {
        $this->actingAs($this->admin);

        DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik,
            'nama' => 'Sertifikat Laravel Developer'
        ]);

        DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik,
            'nama' => 'Sertifikat PHP Developer'
        ]);

        Livewire::test(DataRiwayatSertifikasiResource\Pages\ListDataRiwayatSertifikasis::class)
            ->searchTable('Laravel')
            ->assertCanSeeTableRecords(
                DataRiwayatSertifikasi::where('nama', 'like', '%Laravel%')->get()
            )
            ->assertCanNotSeeTableRecords(
                DataRiwayatSertifikasi::where('nama', 'like', '%PHP%')->get()
            );
    }

    /**
     * Test table column visibility
     */
    public function test_table_column_visibility(): void
    {
        $this->actingAs($this->admin);

        DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik
        ]);

        $component = Livewire::test(DataRiwayatSertifikasiResource\Pages\ListDataRiwayatSertifikasis::class);

        // Test that key columns are visible
        $component->assertSee('Nama');
        $component->assertSee('Nomor');
        $component->assertSee('Tahun');
        $component->assertSee('Status');
    }

    /**
     * Test record ordering
     */
    public function test_record_ordering(): void
    {
        $this->actingAs($this->admin);

        // Create records with different urut values
        $riwayat1 = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik,
            'urut' => 2
        ]);

        $riwayat2 = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $this->pegawai->nik,
            'urut' => 1
        ]);

        Livewire::test(DataRiwayatSertifikasiResource\Pages\ListDataRiwayatSertifikasis::class)
            ->sortTable('urut')
            ->assertCanSeeTableRecords([$riwayat2, $riwayat1], inOrder: true);
    }

    /**
     * Test navigation menu integration
     */
    public function test_navigation_menu_integration(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin');

        $response->assertStatus(200);
        // Check if the navigation item is present
        $response->assertSee('Data Riwayat Sertifikasi');
    }
}