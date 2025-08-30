<?php 

namespace App\Swagger\Auth;

/**
 * @OA\Post(
 *     path="/api/v1/login",
 *     summary="Login user",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password"),
 *             @OA\Property(property="remember_me", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="user", ref="#/components/schemas/User"),
 *             @OA\Property(property="current_tenant_id", type="string", format="uuid"),
 *             @OA\Property(property="access_token", type="string")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Wrong password"),
 *     @OA\Response(response=403, description="Email not verified")
 * )
 */

class Login{}