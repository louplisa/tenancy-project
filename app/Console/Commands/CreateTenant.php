<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:create {tenant} {domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer un tenant avec un nom et un domaine donné';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domain = $this->argument('domain');
        $tenantId = $this->argument('tenant');
        $databaseName = 'tenant_' . $tenantId;

        // Vérifie si le tenant existe déjà
        if (Tenant::find($tenantId)) {
            $this->error("Le tenant '$tenantId' existe déjà.");
            return;
        }

        /** @var Tenant $tenant */
        $tenant = Tenant::create([
            'id' => $tenantId,
            'data' => [
                'driver' => 'mysql',
                'database' => $databaseName,
            ]
        ]);

        // Ajoute un domaine
        $tenant->domains()->create([
            'domain' => $tenantId . '.' . $domain,
        ]);

        $this->info("✅ Tenant '$tenantId' avec domaine '$domain' créé avec succès.");
    }
}
