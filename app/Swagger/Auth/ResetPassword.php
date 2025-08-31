<?php

namespace App\Swagger\Auth;

/**
* @OA\Post(
 *     path="/api/v1/reset-password",
 *     summary="Reset user password",
 *     description="Resets the user's password using the reset token from the email link.",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "token", "password", "password_confirmation"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="token", type="string", example="random-token-from-email"),
 *             @OA\Property(property="password", type="string", format="password", example="newStrongPassword123"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", example="newStrongPassword123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password has been reset successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Password has been reset successfully. Please Login")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid token or email",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid token or email!!")
 *         )
 *     )
 * )
 */

class ResetPassword{}