<?php

namespace App\Swagger\Auth;

/**
 * @OA\Post(
 *     path="/api/v1/register",
 *     summary="Register a new user",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password","password_confirmation"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", example="secret123"),
 *             @OA\Property(property="password_confirmation", type="string", example="secret123"),
 *             @OA\Property(property="create_tenant", type="boolean", example=true),
 *             @OA\Property(property="tenant_name", type="string", example="Tenant1"),
 *             @OA\Property(property="plan", type="string", example="free")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Account created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 */
class Register {}
