<?php

namespace App\Swagger\Tenant;

/**
 * @OA\Get(
 *     path="/api/v1/tenants",
 *     operationId="getUserTenants",
 *     tags={"Tenants"},
 *     summary="Get all tenants for the authenticated user",
 *     description="Returns a list of tenants the authenticated user belongs to, including their role and members count.",
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
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="string", format="uuid", example="31421031-172e-440f-8f8a-4894a2d09de8"),
 *                     @OA\Property(property="name", type="string", example="Team Alpha"),
 *                     @OA\Property(property="slug", type="string", example="team-alpha"),
 *                     @OA\Property(property="plan", type="string", example="free"),
 *                     @OA\Property(property="settings", type="object", nullable=true),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-29T15:33:11.000000Z"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-29T15:33:11.000000Z"),
 *                     @OA\Property(property="role", type="string", example="owner"),
 *                     @OA\Property(property="members_count", type="integer", example=5)
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */
class IndexTenants {}
