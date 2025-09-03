<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\SwitchTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\Models\Tenant;
use App\Models\TenantUser;
use App\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $tenants = $user->tenants()
            ->withCount('users') // counts how many users in each tenant
            ->get()
            ->map(function ($tenant) {
                return [
                    'id' => $tenant->id,
                    'name' => $tenant->name,
                    'slug' => $tenant->slug,
                    'plan' => $tenant->plan,
                    'settings' => $tenant->settings,
                    'created_at' => $tenant->created_at,
                    'updated_at' => $tenant->updated_at,
                    'role' => $tenant->pivot->role,
                    'members_count' => $tenant->users_count,
                ];
            });

        return response()->json([
            'message' => 'Tenants retrieved successfully.',
            'tenants' => $tenants
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTenantRequest $request)
    {
        $user = $request->user();

        return DB::transaction(function () use ($user, $request) {

            $tenant = Tenant::create([
                'name' => $request->name,
                'slug' => SlugService::generateUniqueSlug($request->name),
                'plan' => $request->plan,
            ]);

            TenantUser::create([
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'role' => 'owner',
                'joined_at' => now()
            ]);

            $user->update(['current_tenant_id' => $tenant->id]);

            return response()->json([
                'message' => 'Tenant Created',
                'tenant' => $tenant,
                'current_tenant_id' => $user->current_tenant_id
            ], 200);
        });
    }


    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        $this->authorize('view', $tenant);

        $user = auth()->user();

        // user’s role in this tenant
        $role = $tenant->users()
            ->where('user_id', $user->id)
            ->first()
            ->pivot
            ->role;

        // tenant members with flattened pivot fields
        $members = $tenant->users()
            ->select('users.id', 'users.name', 'users.email')
            ->withPivot('role', 'joined_at')
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'pivot_role' => $member->pivot->role,
                    'pivot_joined_at' => $member->pivot->joined_at,
                ];
            });

        return response()->json([
            'message' => 'Tenant retrieved successfully.',
            'tenant' => $tenant,
            'role' => $role,
            'members' => $members
        ], 200);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTenantRequest $request, Tenant $tenant)
    {
        // Authorize the user for this tenant
        $this->authorize('update', $tenant);

        return DB::transaction(function () use ($request, $tenant) {

            $data = $request->validated();

            // Regenerate slug only if name is changed
            if (isset($data['name']) && $data['name'] !== $tenant->name) {
                $data['slug'] = SlugService::generateUniqueSlug($data['name']);
            }

            $tenant->update($data);

            return response()->json([
                'message' => 'Tenant updated successfully.',
                'tenant' => $tenant,
            ], 200);
        });
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function switchTenant(SwitchTenantRequest $request)
    {
        $user = $request->user();

        if (! $user->tenants()->where('tenant_id', $request->tenant_id)->exists()) {
            return response()->json(['message' => 'Not a member of this tenant'], 403);
        }

        $user->update(['current_tenant_id' => $request->tenant_id]);

        return response()->json([
            'message' => 'Switched tenant',
            'tenant_id' => $request->tenant_id,
        ]);
    }
}
