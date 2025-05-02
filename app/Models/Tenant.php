<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
 * @property array<string> $data
 *
 * @method static TenantCollection all($columns = ['*'])
 */
class Tenant extends Model implements Contracts\Tenant, Contracts\TenantWithDatabase
{
    use Concerns\CentralConnection;
    use Concerns\GeneratesIds;
    use Concerns\HasInternalKeys;
    use Concerns\TenantRun;
    use Concerns\InvalidatesResolverCache;
    use HasDatabase;
    use HasDomains;

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

    public function getTenantKey(): mixed
    {
        return $this->getAttribute($this->getTenantKeyName());
    }

    /**
     * @param array<int|string, static> $models
     * @return TenantCollection<int|string, static>&Collection<int|string, static>
     */
    public function newCollection(array $models = []): TenantCollection
    {
        // @phpstan-ignore-next-line
        return new TenantCollection($models);
    }

    /**
     * @var array<string, class-string>
     */
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

    /**
     * @return BelongsToMany<CentralUser, $this, TenantPivot>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            CentralUser::class,
            'tenant_users',
            'tenant_id',
            'global_user_id',
            'id',
            'global_id'
        )->using(TenantPivot::class);
    }
}
