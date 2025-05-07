<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('central.tenant.index', [
            'tenants' => Tenant::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tenant = null;
        return view('central.tenant.create', [
            'tenant' => $tenant,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tenantId = $request->get('tenant_id');
        $fixedDomainBase = 'localhost';
        $fullDomain = $tenantId . '.' . $fixedDomainBase;

        $validator = Validator::make([
            'tenant_id' => $tenantId,
            'domain' => $fullDomain,
        ], [
            'domain' => 'required|string|unique:domains,domain',
            'tenant_id' => 'required|string|unique:tenants,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $databaseName = 'tenant_' . $tenantId;

        /** @var Tenant $tenant */
        $tenant = Tenant::create([
            'id' => $tenantId,
            'data' => [
                'driver' => 'mysql',
                'database' => $databaseName,
            ]
        ]);

        $tenant->domains()->create([
            'domain' => $fullDomain,
        ]);

        return redirect()->route('tenants.index')->with('success', 'Le tenant a bien été créé');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return redirect()->route('tenants.index')->with('success', 'Le tenant a bien été supprimé');;
    }
}
