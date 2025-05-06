<?php

namespace Tests;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class TenantTestCase extends BaseTestCase
{
    protected $tenancy = false;

    public function setUp(): void
    {
        parent::setUp();

        if (Tenant::query()->where('id', 'test-tenant')->exists()) {
            Tenant::query()->where('id', 'test-tenant')->delete();
        }

        if ($this->tenancy) {
            $this->initializeTenancy();
        }
    }

    public function initializeTenancy()
    {
        $tenant = Tenant::create([
            'id' => 'test-tenant-' . uniqid()
        ]);

        tenancy()->initialize($tenant);
    }

    protected function tearDown(): void
    {
        if (app()->bound('tenancy') && tenancy()->initialized) {
            tenancy()->end();
        }

        DB::disconnect();

        try {
            // Supprimer toutes les bases de données de test
            $databases = DB::select("SELECT SCHEMA_NAME
            FROM information_schema.SCHEMATA
            WHERE SCHEMA_NAME LIKE 'tenant_test-tenant%'");

            foreach ($databases as $database) {
                DB::connection('tenant_template')
                    ->statement("DROP DATABASE IF EXISTS `{$database->SCHEMA_NAME}`");
            }
        } catch (\Exception $e) {
            Log::error("Error while dropping DB: " . $e->getMessage());
        }

        // Supprimer tous les tenants de test
        Tenant::query()->where('id', 'LIKE', 'test-tenant%')->delete();

        parent::tearDown();

    }
}

