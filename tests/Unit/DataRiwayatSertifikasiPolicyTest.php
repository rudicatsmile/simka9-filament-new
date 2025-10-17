<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\DataRiwayatSertifikasi;
use App\Models\DataPegawai;
use App\Models\CwspsRole;
use App\Policies\DataRiwayatSertifikasiPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * DataRiwayatSertifikasiPolicyTest
 * 
 * Unit tests untuk policy DataRiwayatSertifikasi
 * Menguji authorization untuk berbagai role dan permissions
 * 
 * @package Tests\Unit
 * @author SIMKA9 Development Team
 * @version 1.0.0
 */
class DataRiwayatSertifikasiPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected DataRiwayatSertifikasiPolicy $policy;
    protected User $superAdmin;
    protected User $admin;
    protected User $operator;
    protected User $viewer;
    protected User $regularUser;
    protected DataRiwayatSertifikasi $riwayatSertifikasi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new DataRiwayatSertifikasiPolicy();

        // Seed permissions and roles
        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);

        // Get roles
        $superAdminRole = CwspsRole::where('identifier', 'super-admin')->first();
        $adminRole = CwspsRole::where('identifier', 'admin')->first();
        $operatorRole = CwspsRole::where('identifier', 'operator')->first();
        $viewerRole = CwspsRole::where('identifier', 'viewer')->first();

        // Create test users with different roles
        $this->superAdmin = User::factory()->create();
        if ($superAdminRole) {
            $this->superAdmin->assignRole($superAdminRole);
        }

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
        // No role assigned to regular user

        // Create test data
        $pegawai = DataPegawai::factory()->create();
        $this->riwayatSertifikasi = DataRiwayatSertifikasi::factory()->create([
            'nik_data_pegawai' => $pegawai->nik
        ]);
    }

    /**
     * Test viewAny permission for different roles
     */
    public function test_view_any_permission(): void
    {
        // Super Admin should have access
        $this->assertTrue($this->policy->viewAny($this->superAdmin));

        // Admin should have access
        $this->assertTrue($this->policy->viewAny($this->admin));

        // Operator should have access
        $this->assertTrue($this->policy->viewAny($this->operator));

        // Viewer should have access
        $this->assertTrue($this->policy->viewAny($this->viewer));

        // Regular user should not have access
        $this->assertFalse($this->policy->viewAny($this->regularUser));
    }

    /**
     * Test view permission for different roles
     */
    public function test_view_permission(): void
    {
        // Super Admin should have access
        $this->assertTrue($this->policy->view($this->superAdmin, $this->riwayatSertifikasi));

        // Admin should have access
        $this->assertTrue($this->policy->view($this->admin, $this->riwayatSertifikasi));

        // Operator should have access
        $this->assertTrue($this->policy->view($this->operator, $this->riwayatSertifikasi));

        // Viewer should have access
        $this->assertTrue($this->policy->view($this->viewer, $this->riwayatSertifikasi));

        // Regular user should not have access
        $this->assertFalse($this->policy->view($this->regularUser, $this->riwayatSertifikasi));
    }

    /**
     * Test create permission for different roles
     */
    public function test_create_permission(): void
    {
        // Super Admin should have access
        $this->assertTrue($this->policy->create($this->superAdmin));

        // Admin should have access
        $this->assertTrue($this->policy->create($this->admin));

        // Operator should have access
        $this->assertTrue($this->policy->create($this->operator));

        // Viewer should not have access
        $this->assertFalse($this->policy->create($this->viewer));

        // Regular user should not have access
        $this->assertFalse($this->policy->create($this->regularUser));
    }

    /**
     * Test update permission for different roles
     */
    public function test_update_permission(): void
    {
        // Super Admin should have access
        $this->assertTrue($this->policy->update($this->superAdmin, $this->riwayatSertifikasi));

        // Admin should have access
        $this->assertTrue($this->policy->update($this->admin, $this->riwayatSertifikasi));

        // Operator should have access
        $this->assertTrue($this->policy->update($this->operator, $this->riwayatSertifikasi));

        // Viewer should not have access
        $this->assertFalse($this->policy->update($this->viewer, $this->riwayatSertifikasi));

        // Regular user should not have access
        $this->assertFalse($this->policy->update($this->regularUser, $this->riwayatSertifikasi));
    }

    /**
     * Test delete permission for different roles
     */
    public function test_delete_permission(): void
    {
        // Super Admin should have access
        $this->assertTrue($this->policy->delete($this->superAdmin, $this->riwayatSertifikasi));

        // Admin should have access
        $this->assertTrue($this->policy->delete($this->admin, $this->riwayatSertifikasi));

        // Operator should not have access
        $this->assertFalse($this->policy->delete($this->operator, $this->riwayatSertifikasi));

        // Viewer should not have access
        $this->assertFalse($this->policy->delete($this->viewer, $this->riwayatSertifikasi));

        // Regular user should not have access
        $this->assertFalse($this->policy->delete($this->regularUser, $this->riwayatSertifikasi));
    }

    /**
     * Test restore permission for different roles
     */
    public function test_restore_permission(): void
    {
        // Super Admin should have access
        $this->assertTrue($this->policy->restore($this->superAdmin, $this->riwayatSertifikasi));

        // Admin should have access
        $this->assertTrue($this->policy->restore($this->admin, $this->riwayatSertifikasi));

        // Operator should not have access
        $this->assertFalse($this->policy->restore($this->operator, $this->riwayatSertifikasi));

        // Viewer should not have access
        $this->assertFalse($this->policy->restore($this->viewer, $this->riwayatSertifikasi));

        // Regular user should not have access
        $this->assertFalse($this->policy->restore($this->regularUser, $this->riwayatSertifikasi));
    }

    /**
     * Test forceDelete permission for different roles
     */
    public function test_force_delete_permission(): void
    {
        // Super Admin should have access
        $this->assertTrue($this->policy->forceDelete($this->superAdmin, $this->riwayatSertifikasi));

        // Admin should not have access
        $this->assertFalse($this->policy->forceDelete($this->admin, $this->riwayatSertifikasi));

        // Operator should not have access
        $this->assertFalse($this->policy->forceDelete($this->operator, $this->riwayatSertifikasi));

        // Viewer should not have access
        $this->assertFalse($this->policy->forceDelete($this->viewer, $this->riwayatSertifikasi));

        // Regular user should not have access
        $this->assertFalse($this->policy->forceDelete($this->regularUser, $this->riwayatSertifikasi));
    }

    /**
     * Test download permission for different roles
     */
    public function test_download_permission(): void
    {
        // Super Admin should have access
        $this->assertTrue($this->policy->download($this->superAdmin, $this->riwayatSertifikasi));

        // Admin should have access
        $this->assertTrue($this->policy->download($this->admin, $this->riwayatSertifikasi));

        // Operator should have access
        $this->assertTrue($this->policy->download($this->operator, $this->riwayatSertifikasi));

        // Viewer should have access
        $this->assertTrue($this->policy->download($this->viewer, $this->riwayatSertifikasi));

        // Regular user should not have access
        $this->assertFalse($this->policy->download($this->regularUser, $this->riwayatSertifikasi));
    }

    /**
     * Test policy with null user (guest)
     */
    public function test_policy_with_null_user(): void
    {
        // All permissions should be false for null user
        $this->assertFalse($this->policy->viewAny(null));
        $this->assertFalse($this->policy->view(null, $this->riwayatSertifikasi));
        $this->assertFalse($this->policy->create(null));
        $this->assertFalse($this->policy->update(null, $this->riwayatSertifikasi));
        $this->assertFalse($this->policy->delete(null, $this->riwayatSertifikasi));
        $this->assertFalse($this->policy->restore(null, $this->riwayatSertifikasi));
        $this->assertFalse($this->policy->forceDelete(null, $this->riwayatSertifikasi));
        $this->assertFalse($this->policy->download(null, $this->riwayatSertifikasi));
    }

    /**
     * Test policy methods exist and are callable
     */
    public function test_policy_methods_exist(): void
    {
        $this->assertTrue(method_exists($this->policy, 'viewAny'));
        $this->assertTrue(method_exists($this->policy, 'view'));
        $this->assertTrue(method_exists($this->policy, 'create'));
        $this->assertTrue(method_exists($this->policy, 'update'));
        $this->assertTrue(method_exists($this->policy, 'delete'));
        $this->assertTrue(method_exists($this->policy, 'restore'));
        $this->assertTrue(method_exists($this->policy, 'forceDelete'));
        $this->assertTrue(method_exists($this->policy, 'download'));
    }

    /**
     * Test download permission with file
     */
    public function test_download_permission_with_file(): void
    {
        $riwayatWithFile = DataRiwayatSertifikasi::factory()->create([
            'berkas' => 'sertifikasi/test-certificate.pdf'
        ]);

        $riwayatWithoutFile = DataRiwayatSertifikasi::factory()->create([
            'berkas' => null
        ]);

        // Should be able to download if user has permission and file exists
        $this->assertTrue($this->policy->download($this->admin, $riwayatWithFile));
        
        // Should still return true even if no file (let controller handle the logic)
        $this->assertTrue($this->policy->download($this->admin, $riwayatWithoutFile));
        
        // Should not be able to download if user doesn't have permission
        $this->assertFalse($this->policy->download($this->regularUser, $riwayatWithFile));
    }
}