<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Contracts;
use Stancl\Tenancy\Database\Concerns;
use Stancl\Tenancy\Database\Models\TenantPivot;
use Stancl\Tenancy\Database\TenantCollection;
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Concerns\HasDatabase;

/**
 * @property string|int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property array $data
 *
 * @method static TenantCollection all($columns = ['*'])
 */
class Tenant extends Model implements Contracts\Tenant, Contracts\TenantWithDatabase
{
    use Concerns\CentralConnection,
        Concerns\GeneratesIds,
        Concerns\HasInternalKeys,
        Concerns\TenantRun,
        Concerns\InvalidatesResolverCache,
        HasDatabase,
        HasDomains;

    protected static $modelsShouldPreventAccessingMissingAttributes = false;

    protected $table = 'tenants';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'data',
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function getTenantKeyName(): string
    {
        return 'id';
    }

    public function getTenantKey()
    {
        return $this->getAttribute($this->getTenantKeyName());
    }

    public function newCollection(array $models = []): TenantCollection
    {
        return new TenantCollection($models);
    }

    protected $dispatchesEvents = [
        'saving' => Events\SavingTenant::class,
        'saved' => Events\TenantSaved::class,
        'creating' => Events\CreatingTenant::class,
        'created' => Events\TenantCreated::class,
        'updating' => Events\UpdatingTenant::class,
        'updated' => Events\TenantUpdated::class,
        'deleting' => Events\DeletingTenant::class,
        'deleted' => Events\TenantDeleted::class,
    ];

    public function users()
    {
        return $this->belongsToMany(CentralUser::class, 'tenant_users', 'tenant_id', 'global_user_id', 'id', 'global_id')
            ->using(TenantPivot::class);
    }
}
