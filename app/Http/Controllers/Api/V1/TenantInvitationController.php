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
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;

class TenantInvitationController extends Controller
{
    public function send(SendInvitationRequest $request)
    {
        $this->authorize('send', TenantInvitation::class);

        $validated = $request->validated();

        return DB::transaction(function () use ($validated) {
            $token = Str::random(64);

            $invitation = TenantInvitation::create([
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

    public function accept(string $invitationId, string $token)
    {
        $invitation = TenantInvitation::withoutGlobalScopes()->findOrFail($invitationId);

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

            TenantInvitation::withoutGlobalScopes()
                ->where('tenant_id', $invitation->tenant_id)
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

            return response()->json([
                'message' => 'Invitation accepted successfully.',
                'user_id' => $user->id,
                'tenant_id' => $invitation->tenant_id,
            ]);
        });
    }



    public function registerWithInvitation(RegisterWithInvitationRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $invitation = TenantInvitation::withoutGlobalScopes()->findOrFail($request->invitation_id);

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

            TenantInvitation::withoutGlobalScopes()
                ->where('email', $invitation->email)
                ->whereNull('accepted_at')
                ->where('id', '!=', $invitation->id)
                ->update(['revoked' => true]);

            event(new Registered($user));

            return response()->json([
                'message' => 'Registration successful, tenant joined. Please Login',
                'user' => new UserResource($user),
                'tenant' => [
                    'id' => $invitation->tenant_id,
                    'role' => $invitation->role,
                ],
            ], 201);
        });
    }


    public function pendingInvitations()
    {
        $this->authorize('viewAny', TenantInvitation::class);
        $invitations = TenantInvitation::whereNull('accepted_at')
            ->where('revoked', false)
            ->get();

        return response()->json([
            'message' => 'Pending invitations retrieved successfully.',
            'invitations' => $invitations
        ], 200);
    }

    public function revoke(TenantInvitation $invitation)
    {
        $this->authorize('revoke', $invitation);
        if ($invitation->accepted_at || $invitation->revoked) {
            return response()->json([
                'message' => 'This invitation cannot be revoked.'
            ], 422);
        }

        $invitation->update(['revoked' => true]);

        return response()->json([
            'message' => 'Invitation revoked successfully.'
        ], 200);
    }
}
