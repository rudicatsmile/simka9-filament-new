<?php

namespace Tests\Unit\Policies;

use App\Models\DataRiwayatUnitKerjaLain;
use App\Models\User;
use App\Policies\DataRiwayatUnitKerjaLainPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataRiwayatUnitKerjaLainPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected DataRiwayatUnitKerjaLainPolicy $policy;
    protected User $user;
    protected DataRiwayatUnitKerjaLain $riwayat;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->policy = new DataRiwayatUnitKerjaLainPolicy();
        $this->user = User::factory()->create();
        $this->riwayat = DataRiwayatUnitKerjaLain::factory()->create();
    }

    /** @test */
    public function it_allows_view_any_for_authorized_users()
    {
        // Mock user with permission
        $this->user->shouldReceive('can')
            ->with('data-riwayat-unit-kerja-lains.view')
            ->andReturn(true);

        $result = $this->policy->viewAny($this->user);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_denies_view_any_for_unauthorized_users()
    {
        // Mock user without permission
        $this->user->shouldReceive('can')
            ->with('data-riwayat-unit-kerja-lains.view')
            ->andReturn(false);

        $result = $this->policy->viewAny($this->user);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_allows_view_for_authorized_users()
    {
        // Mock user with permission
        $this->user->shouldReceive('can')
            ->with('data-riwayat-unit-kerja-lains.view')
            ->andReturn(true);

        $result = $this->policy->view($this->user, $this->riwayat);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_denies_view_for_unauthorized_users()
    {
        // Mock user without permission
        $this->user->shouldReceive('can')
            ->with('data-riwayat-unit-kerja-lains.view')
            ->andReturn(false);

        $result = $this->policy->view($this->user, $this->riwayat);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_allows_create_for_authorized_users()
    {
        // Mock user with permission
        $this->user->shouldReceive('can')
            ->with('data-riwayat-unit-kerja-lains.create')
            ->andReturn(true);

        $result = $this->policy->create($this->user);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_denies_create_for_unauthorized_users()
    {
        // Mock user without permission
        $this->user->shouldReceive('can')
            ->with('data-riwayat-unit-kerja-lains.create')
            ->andReturn(false);

        $result = $this->policy->create($this->user);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_allows_update_for_authorized_users()
    {
        // Mock user with permission
        $this->user->shouldReceive('can')
            ->with('data-riwayat-unit-kerja-lains.edit')
            ->andReturn(true);

        $result = $this->policy->update($this->user, $this->riwayat);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_denies_update_for_unauthorized_users()
    {
        // Mock user without permission
        $this->user->shouldReceive('can')
            ->with('data-riwayat-unit-kerja-lains.edit')
            ->andReturn(false);

        $result = $this->policy->update($this->user, $this->riwayat);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_allows_delete_for_authorized_users()
    {
        // Mock user with permission
        $this->user->shouldReceive('can')
            ->with('data-riwayat-unit-kerja-lains.delete')
            ->andReturn(true);

        $result = $this->policy->delete($this->user, $this->riwayat);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_denies_delete_for_unauthorized_users()
    {
        // Mock user without permission
        $this->user->shouldReceive('can')
            ->with('data-riwayat-unit-kerja-lains.delete')
            ->andReturn(false);

        $result = $this->policy->delete($this->user, $this->riwayat);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_allows_restore_for_authorized_users()
    {
        // Mock user with permission
        $this->user->shouldReceive('can')
            ->with('data-riwayat-unit-kerja-lains.restore')
            ->andReturn(true);

        $result = $this->policy->restore($this->user, $this->riwayat);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_denies_restore_for_unauthorized_users()
    {
        // Mock user without permission
        $this->user->shouldReceive('can')
            ->with('data-riwayat-unit-kerja-lains.restore')
            ->andReturn(false);

        $result = $this->policy->restore($this->user, $this->riwayat);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_allows_force_delete_for_authorized_users()
    {
        // Mock user with permission
        $this->user->shouldReceive('can')
            ->with('data-riwayat-unit-kerja-lains.force-delete')
            ->andReturn(true);

        $result = $this->policy->forceDelete($this->user, $this->riwayat);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_denies_force_delete_for_unauthorized_users()
    {
        // Mock user without permission
        $this->user->shouldReceive('can')
            ->with('data-riwayat-unit-kerja-lains.force-delete')
            ->andReturn(false);

        $result = $this->policy->forceDelete($this->user, $this->riwayat);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_has_all_required_policy_methods()
    {
        $requiredMethods = [
            'viewAny',
            'view',
            'create',
            'update',
            'delete',
            'restore',
            'forceDelete',
        ];

        foreach ($requiredMethods as $method) {
            $this->assertTrue(
                method_exists($this->policy, $method),
                "Policy method '{$method}' does not exist"
            );
        }
    }

    /** @test */
    public function it_returns_boolean_values_for_all_methods()
    {
        // Mock user with all permissions
        $this->user->shouldReceive('can')->andReturn(true);

        $this->assertIsBool($this->policy->viewAny($this->user));
        $this->assertIsBool($this->policy->view($this->user, $this->riwayat));
        $this->assertIsBool($this->policy->create($this->user));
        $this->assertIsBool($this->policy->update($this->user, $this->riwayat));
        $this->assertIsBool($this->policy->delete($this->user, $this->riwayat));
        $this->assertIsBool($this->policy->restore($this->user, $this->riwayat));
        $this->assertIsBool($this->policy->forceDelete($this->user, $this->riwayat));
    }
}