<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\TenantInvitationCreated;
use App\Http\Requests\SendInvitationRequest;
use App\Models\TenantInvitation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterWithInvitationRequest;
use App\Http\Resources\UserResource;

class TenantInvitationController extends Controller
{
    public function send(SendInvitationRequest $request)
    {
        $user = $request->user();
        $tenantId = $user->current_tenant_id;

        $this->authorize('send', [TenantInvitation::class, $tenantId]);

        $validated = $request->validated();

        return DB::transaction(function () use ($validated, $tenantId) {
            $token = Str::random(64);

            $invitation = TenantInvitation::create([
                'tenant_id'  => $tenantId,
                'email'      => strtolower($validated['email']),
                'role'       => $validated['role'],
                'token_hash' => Hash::make($token),
                'expires_at' => now()->addDays(7),
            ]);

            event(new TenantInvitationCreated($invitation, $token));

            return response()->json([
                'message' => 'Invitation sent successfully.',
                'invitation' => [
                    'id' => $invitation->id,
                    'email' => $invitation->email,
                    'role' => $invitation->role,
                    'expires_at' => $invitation->expires_at,
                ]
            ], 201);
        });
    }

    public function accept(TenantInvitation $invitation, string $token)
    {
        if (! Hash::check($token, $invitation->token_hash)) {
            return response()->json(['message' => 'Invalid invitation token.'], 403);
        }

        if (! $invitation->isValid()) {
            return response()->json(['message' => 'This invitation is no longer valid.'], 422);
        }

        return DB::transaction(function () use ($invitation, $token) {
            $user = User::where('email', $invitation->email)->first();

            if (! $user) {
                return response()->json([
                    'requires_registration' => true,
                    'email' => $invitation->email,
                    'tenant_id' => $invitation->tenant_id,
                    'role' => $invitation->role,
                    'invitation_id' => $invitation->id,
                    'invitation_token' => $token,
                ]);
            }

            $invitation->update(['accepted_at' => now()]);
            TenantInvitation::where('tenant_id', $invitation->tenant_id)
                ->where('email', $invitation->email)
                ->whereNull('accepted_at')
                ->where('id', '!=', $invitation->id)
                ->update(['revoked' => true]);

            $user->tenants()->syncWithoutDetaching([
                $invitation->tenant_id => [
                    'role' => $invitation->role,
                    'joined_at' => now(),
                    'accepted_at' => now(),
                ]
            ]);

            $authToken = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'message' => 'Invitation accepted successfully.',
                'user_id' => $user->id,
                'tenant_id' => $invitation->tenant_id,
                'token' => $authToken
            ]);
        });
    }


    public function registerWithInvitation(RegisterWithInvitationRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $invitation = TenantInvitation::findOrFail($request->invitation_id);

            if (! Hash::check($request->invitation_token, $invitation->token_hash)) {
                return response()->json(['message' => 'Invalid invitation token.'], 403);
            }

            if (! $invitation->isValid()) {
                return response()->json(['message' => 'This invitation is no longer valid.'], 422);
            }

            if (User::where('email', $invitation->email)->exists()) {
                return response()->json([
                    'message' => 'An account with this email already exists. Please log in and accept the invitation from your account.'
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $invitation->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
                'current_tenant_id' => $invitation->tenant_id,
            ]);

            $user->tenants()->syncWithoutDetaching([
                $invitation->tenant_id => [
                    'role' => $invitation->role,
                    'joined_at' => now(),
                ],
            ]);

            $invitation->update(['accepted_at' => now()]);
            TenantInvitation::where('tenant_id', $invitation->tenant_id)
                ->where('email', $invitation->email)
                ->whereNull('accepted_at')
                ->where('id', '!=', $invitation->id)
                ->update(['revoked' => true]);

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'message' => 'Registration successful, tenant joined.',
                'user' => new UserResource($user),
                'tenant' => [
                    'id' => $invitation->tenant_id,
                    'role' => $invitation->role,
                ],
                'token' => $token,
            ], 201);
        });
    }
}
