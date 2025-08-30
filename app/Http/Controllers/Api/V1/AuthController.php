<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\RefreshToken;
use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use App\Services\SlugService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        if (! $user->email_verified_at) {
            return response()->json([
                'message' => 'Please verify your email before logging in.'
            ], 403);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'wrong Password'
            ], 401);
        }

        $user->tokens()->delete();
        $user->refreshTokens()->delete();

        $accessToken = $user->createToken('api-token', [], now()->addHours(6))->plainTextToken;
        $refreshToken = Str::random(64);
        $expiresAt = $request->remember_me ? now()->addDays(90) : now()->addDays(30);

        $user->refreshTokens()->create([
            'token' => $refreshToken,
            'expires_at' => $expiresAt
        ]);

        $cookie = cookie('refresh_token', $refreshToken, $expiresAt->diffInMinutes(), null, null, true, true);


        return response()->json([
            'message' => 'Login successful',
            'user' => new UserResource($user),
            'current_tenant_id' => $user->current_tenant_id,
            'access_token' => $accessToken
        ], 200)->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        $refreshToken = $request->cookie('refresh_token');
        if ($refreshToken) {
            $user->refreshTokens()->delete();
        }

        $cookie = cookie()->forget('refresh_token');

        return response()->json([
            'message' => 'You are Logged out'
        ], 200)->withCookie($cookie);
    }

    public function refreshToken(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');

        if (! $refreshToken) {
            return response()->json([
                'message' => 'No refresh token provided!'
            ], 401);
        }

        $tokenRecord = RefreshToken::where('token', $refreshToken)->first();

        if (! $tokenRecord || ! $tokenRecord->isValid()) {
            return response()->json([
                'message' => 'Refresh token not found or expired. Please login again.'
            ], 401);
        }

        $user = $tokenRecord->user;

        $user->tokens()->delete();

        $accessToken = $user->createToken('api-token', [], now()->addHours(6))->plainTextToken;

        $cookie = cookie(
            'refresh_token',
            $tokenRecord->token,
            max($tokenRecord->expires_at->diffInMinutes(), 1),
            null,
            null,
            true,
            true
        );

        return response()->json([
            'message' => 'Access token refreshed successfully',
            'access_token' => $accessToken,
            'user' => new UserResource($user),
            'current_tenant_id' => $user->current_tenant_id,
        ], 200)->withCookie($cookie);
    }
}
