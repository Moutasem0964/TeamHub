<?php

namespace App\Swagger\Auth;

/**
 * @OA\Post(
 *     path="/api/v1/logout",
 *     summary="Logout the authenticated user",
 *     tags={"Auth"},
 *     security={{"sanctum": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Logout successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Logout successful")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized (invalid or missing token)",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated")
 *         )
 *     )
 * )
 */

class Logout{}