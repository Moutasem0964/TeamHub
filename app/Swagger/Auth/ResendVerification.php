<?php

namespace App\Swagger\Auth;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/v1/email/resend",
 *     summary="Resend email verification",
 *     description="Resends the verification email to the user if not verified yet.",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="email", type="string", example="example@gmail.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Email verification status",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Verification email resent or Email already verified")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No user found with this email"
 *     )
 * )
 */
class ResendVerification {}
