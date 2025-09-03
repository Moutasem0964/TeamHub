<?php

namespace App\Policies;

use App\Models\Tenant;
use App\Models\TenantInvitation;
use App\Models\TenantUser;
use App\Models\User;
use App\Services\CacheService;
use Illuminate\Auth\Access\Response;

class TenantInvitationPolicy
{

    protected CacheService $cache;

    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $role = $this->cache->roleCheck($user, app('tenant_id'));
        return in_array($role, ['owner', 'admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TenantInvitation $tenantInvitation): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TenantInvitation $tenantInvitation): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function revoke(User $user, TenantInvitation $tenantInvitation): bool
    {
        $role = $this->cache->roleCheck($user, $tenantInvitation->tenant_id);
        return in_array($role, ['owner', 'admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TenantInvitation $tenantInvitation): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TenantInvitation $tenantInvitation): bool
    {
        //
    }

    public function send(User $user)
    {
        $role = $this->cache->roleCheck($user, app('tenant_id'));
        return in_array($role, ['owner', 'admin']);
    }
}
