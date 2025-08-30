<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
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

            $tenantId = null;

            if (!empty($validated['create_tenant']) && $validated['create_tenant'] === true) {
                $tenantName = $validated['tenant_name'];

                $tenant = Tenant::create([
                    'name' => $tenantName,
                    'slug' => SlugService::generateUniqueSlug($tenantName),
                    'plan' => $validated['plan'],
                ]);

                TenantUser::create([
                    'tenant_id'   => $tenant->id,
                    'user_id'     => $user->id,
                    'role'        => 'owner',
                    'joined_at'   => now(),
                    'accepted_at' => now(),
                ]);

                $tenantId = $tenant->id;
            }
            if ($tenantId) {
                $user->update(['current_tenant_id' => $tenant->id]);
            }


            event(new Registered($user));

            return response()->json([
                'message' => 'Account created. Please verify your email before logging in.',
                'user'    => new UserResource($user),
            ], 201);
        });
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'wrong Password'
            ], 401);
        }
        $user->tokens()->delete();
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'message' => 'Login successful',
            'user' => new UserResource($user),
            'current_tenant_id' => $user->current_tenant_id,
            'token' => $token
        ], 200);
    }
}
