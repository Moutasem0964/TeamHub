<?php

namespace App\Policies;

use App\Models\TenantInvitation;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TenantInvitationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return TenantUser::where('user_id', $user->id)->where('tenant_id', app('tenant_id'))
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
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
        return TenantUser::where('tenant_id', $tenantInvitation->tenant_id)
            ->where('user_id', $user->id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
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
        return TenantUser::where('user_id', $user->id)
            ->where('tenant_id', app('tenant_id'))
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }
}
