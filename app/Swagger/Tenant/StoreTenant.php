<?php

namespace App\Swagger\Tenant;

/**
 * @OA\Post(
 *     path="/api/v1/tenants",
 *     operationId="createTenant",
 *     tags={"Tenants"},
 *     summary="Create a new tenant",
 *     description="Creates a new tenant and sets the current user as the owner.",
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","plan"},
 *             @OA\Property(property="name", type="string", example="Acme Inc."),
 *             @OA\Property(property="plan", type="string", example="free")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tenant created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Tenant Created"),
 *             @OA\Property(property="tenant", ref="#/components/schemas/Tenant"),
 *             @OA\Property(property="current_tenant_id", type="string", format="uuid", example="31421031-172e-440f-8f8a-4894a2d09de8")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */

class StoreTenant{}