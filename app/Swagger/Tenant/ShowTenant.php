<?php

namespace App\Swagger\Tenant;

/**
 * @OA\Get(
 *     path="/api/v1/tenants/{id}",
 *     operationId="getTenantDetails",
 *     tags={"Tenants"},
 *     summary="Get details of a specific tenant",
 *     description="Returns tenant details, the current user role, and the list of tenant members.",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="UUID of the tenant",
 *         @OA\Schema(type="string", format="uuid")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tenant retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Tenant retrieved successfully."),
 *             @OA\Property(
 *                 property="tenant",
 *                 ref="#/components/schemas/Tenant"
 *             ),
 *             @OA\Property(property="role", type="string", example="member"),
 *             @OA\Property(
 *                 property="members",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="string", format="uuid", example="4b928c84-4d52-4745-9920-9d8d8a28df49"),
 *                     @OA\Property(property="name", type="string", example="John Doe"),
 *                     @OA\Property(property="email", type="string", example="john@example.com"),
 *                     @OA\Property(property="pivot_role", type="string", example="owner"),
 *                     @OA\Property(property="pivot_joined_at", type="string", format="date-time", example="2025-08-29T15:32:31.000000Z")
 *                 )
 *             ),
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=404, description="Tenant not found")
 * )
 */
class ShowTenant {}
