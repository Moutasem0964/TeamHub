<?php

namespace App\Swagger\Tenant;

/**
 * @OA\Post(
 *     path="/api/v1/tenants/switch",
 *     summary="Switch active tenant",
 *     tags={"Tenants"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"tenant_id"},
 *             @OA\Property(property="tenant_id", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tenant switched successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="active_tenant_id", type="string")
 *         )
 *     ),
 *     @OA\Response(response=422, description="Tenant not found or inaccessible")
 * )
 */
class SwitchTenant {}