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
        // Validate token
        if (!Hash::check($token, $invitation->token_hash)) {
            return response()->json(['message' => 'Invalid or expired token.'], 403);
        }

        // Check if invitation is still valid
        if ($invitation->expires_at->isPast() || $invitation->accepted_at || $invitation->revoked) {
            return response()->json(['message' => 'This invitation is no longer valid.'], 422);
        }

        return DB::transaction(function () use ($invitation, $token) {

            // If the invited user exists
            $user = User::where('email', $invitation->email)->first();

            if (!$user) {
                // No user registered yet → frontend should show registration form
                return response()->json([
                    'requires_registration' => true,
                    'email' => $invitation->email,
                    'tenant_id' => $invitation->tenant_id,
                    'role' => $invitation->role,
                    'invitation_id' => $invitation->id,
                    'invitation_token' => $token,
                ]);
            }

            // User exists → accept invitation immediately
            $invitation->update(['accepted_at' => now()]);

            // Revoke other invitations
            TenantInvitation::where('tenant_id', $invitation->tenant_id)
                ->where('email', $invitation->email)
                ->whereNull('accepted_at')
                ->where('id', '!=', $invitation->id)
                ->update(['revoked' => true]);

            // Attach user to tenant
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
}
