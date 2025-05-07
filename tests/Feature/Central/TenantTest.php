<?php

namespace Tests\Feature\Central;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_the_tenant_index_page(): void
    {
        $response = $this->get('http://localhost/tenants');

        $response->assertStatus(200);
        $response->assertViewIs('central.tenant.index');
    }

    public function test_show_the_create_tenant_form(): void
    {
        $response = $this->get('http://localhost/tenants/create');

        $response->assertStatus(200);
        $response->assertViewIs('central.tenant.create');
    }

    public function test_create_a_new_tenant(): void
    {
        $tenantId = 'test-tenant';

        $response = $this->post('http://localhost/tenants', [
            'tenant_id' => $tenantId,
        ]);

        $response->assertRedirect(route('tenants.index'));
        $this->assertDatabaseHas('tenants', [
            'id' => $tenantId,
        ]);

        $this->assertDatabaseHas('domains', [
            'domain' => $tenantId . '.localhost',
        ]);
    }

    public function test_validate_tenant_creation(): void
    {
        Tenant::create(['id' => 'test-tenant']);
        $response = $this->post('http://localhost/tenants', [
            'tenant_id' => 'test-tenant',
        ]);

        $response->assertSessionHasErrors(['tenant_id']);
    }

    public function test_delete_a_tenant(): void
    {
        $tenant = Tenant::create(['id' => 'test-tenant']);

        $response = $this->delete("http://localhost/tenants/{$tenant->id}");

        $response->assertRedirect(route('tenants.index'));
        $this->assertDatabaseMissing('tenants', [
            'id' => $tenant->id,
        ]);
    }
}
