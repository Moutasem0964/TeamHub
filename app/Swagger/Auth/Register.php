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
 *             required={"name","email","password"},
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="password", type="string", format="password"),
 *             @OA\Property(property="password_confirmation", type="string", format="password"),
 *             @OA\Property(property="create_tenant", type="boolean"),
 *             @OA\Property(property="tenant_name", type="string"),
 *             @OA\Property(property="plan", type="string", enum={"free","plus"})
 *         )
 *     ),
 *     @OA\Response(response=201, description="Account created successfully"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */

class Register {}
