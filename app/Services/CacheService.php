<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Tenant;

class CacheService
{
    protected int $ttl;

    public function __construct()
    {
        $this->ttl = 60; // default cache time in minutes
    }

    /**
     * Generic cache getter with fallback callback.
     */
    public function get(string $key, callable $callback, ?int $ttl = null)
    {
        return Cache::remember($key, $ttl ?? $this->ttl, $callback);
    }

    /**
     * Put a value into the cache.
     */
    public function put(string $key, mixed $value, ?int $ttl = null): void
    {
        Cache::put($key, $value, $ttl ?? $this->ttl);
    }

    /**
     * Remove a value from the cache.
     */
    public function forget(string $key): void
    {
        Cache::forget($key);
    }

    /**
     * Get a user's role in a tenant (cached).
     */
    public function roleCheck(User $user, string $tenantId): ?string
    {
        $key = "tenant_user_{$tenantId}_{$user->id}_role";

        return $this->get($key, function () use ($tenantId, $user) {
            $pivot = $user->tenants()
                ->where('tenant_id', $tenantId)
                ->first()?->pivot;

            return $pivot?->role ?? null;
        });
    }


    public function isTenantMember(User $user, string $tenantId): bool
    {
        return $this->roleCheck($user, $tenantId) !== null;
    }

    public function clearRoleCache(User $user, string $tenantId): void
    {
        $key = "tenant_user_{$tenantId}_{$user->id}_role";
        $this->forget($key);
    }
}
