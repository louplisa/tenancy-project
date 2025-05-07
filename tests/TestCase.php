<?php

namespace Tests;

use App\Models\CentralUser;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = CentralUser::factory()->create();
        $this->actingAs($this->user);

        config(['tenancy.central_domains' => ['localhost']]);
    }

    protected function tearDown(): void
    {
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
