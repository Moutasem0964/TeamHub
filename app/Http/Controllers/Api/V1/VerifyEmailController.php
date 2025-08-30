<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ResendVerificationEmailRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class VerifyEmailController extends Controller
{
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 200);
        }

        if (! URL::hasValidSignature($request)) {
            return response()->json(['message' => 'Invalid or expired verification link.'], 403);
        }

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link.'], 403);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json([
            'message' => 'Email successfully verified. Please Login',
            'user'    => new UserResource($user),
        ], 200);
    }

    public function resend(ResendVerificationEmailRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 200);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email resent.'], 200);
    }
}
