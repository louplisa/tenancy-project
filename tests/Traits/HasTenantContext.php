<?php

namespace Tests\Traits;

use App\Models\Tenant;

trait HasTenantContext
{
    /**
     * Exécute une action dans le contexte d'un tenant.
     *
     * @param \Closure $callback
     * @return mixed
     */
    public function withTenantContext(\Closure $callback)
    {
        $tenant = $this->createTenant();
        return $tenant->run(fn () => $callback());
    }

    /**
     * Crée un tenant.
     *
     * @return Tenant
     */
    protected function createTenant(): Tenant
    {
        return Tenant::create([
            'id' => 'test-tenant-' . uniqid()
        ]);
    }
}
