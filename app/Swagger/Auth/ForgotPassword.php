<?php

namespace App\Swagger\Auth;

    /**
 * @OA\Post(
 *     path="/api/v1/forgot-password",
 *     summary="Request a password reset link",
 *     description="Sends a password reset link to the provided email if it exists.",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password reset link sent successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Password reset link sent to your email")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Unable to send reset link",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unable to send reset link")
 *         )
 *     )
 * )
 */
class ForgotPassword{}