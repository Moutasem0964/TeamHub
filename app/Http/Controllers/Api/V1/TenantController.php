<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\SwitchTenantRequest;
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

        $tenants = $user->tenants()->get();

        return response()->json([
            'message' => 'Tenants retrieved successfully.',
            'tenants' => $tenants,
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
