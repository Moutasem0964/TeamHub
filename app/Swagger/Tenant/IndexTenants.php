<?php

namespace App\Swagger\Tenant;

/**
 * @OA\Get(
 *     path="/api/v1/tenants",
 *     operationId="getUserTenants",
 *     tags={"Tenants"},
 *     summary="Get all tenants for the authenticated user",
 *     description="Returns a list of tenants the authenticated user belongs to.",
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Tenants retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Tenants retrieved successfully."),
 *             @OA\Property(
 *                 property="tenants",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Tenant")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */
class IndexTenants {}
