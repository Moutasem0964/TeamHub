<?php

namespace App\Swagger\Auth;

use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v1/email/verify/{id}/{hash}",
 *     summary="Verify user email",
 *     description="Verifies the user's email and returns an API token.",
 *     tags={"Auth"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="User ID",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="hash",
 *         in="path",
 *         description="SHA1 hash of the user's email",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Email successfully verified. Please Login!",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="user", ref="#/components/schemas/User"),
 *         )
 *     ),
 *     @OA\Response(response=403, description="Invalid or expired verification link"),
 *     @OA\Response(response=404, description="User not found")
 * )
 */
class EmailVerification {}
