<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use App\Services\SlugService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(StoreRegisterRequest $request)
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated) {

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => strtolower($validated['email']),
                'password' => Hash::make($validated['password']),
            ]);

            // Optionally create a tenant
            if (!empty($validated['create_tenant']) && $validated['create_tenant'] === true) {
                $tenantName = $validated['tenant_name'];

                $tenant = Tenant::create([
                    'name' => $tenantName,
                    'slug' => SlugService::generateUniqueSlug($tenantName),
                    'plan' => $validated['plan'], // 'free' or 'plus'
                ]);

                TenantUser::create([
                    'tenant_id'   => $tenant->id,
                    'user_id'     => $user->id,
                    'role'        => 'owner',
                    'joined_at'   => now(),
                    'accepted_at' => now(),
                ]);
            }

            // Trigger email verification notification
            event(new Registered($user));

            // For security, do NOT return tokens here. Ask user to verify first.
            return response()->json([
                'message' => 'Account created. Please verify your email before logging in.',
                'user'    => new UserResource($user),
            ], 201);
        });
    }
}
