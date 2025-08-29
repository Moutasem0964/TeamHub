<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SwitchTenantRequest;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
