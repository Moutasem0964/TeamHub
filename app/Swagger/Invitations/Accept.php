<?php

namespace App\Swagger\Invitations;

/**
 * @OA\Post(
 *     path="/api/v1/tenants/invitations/accept/{token}",
 *     summary="Accept a tenant invitation",
 *     tags={"Tenant Invitations"},
 *     @OA\Parameter(
 *         name="token",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Invitation accepted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="tenant_id", type="string"),
 *             @OA\Property(property="role", type="string")
 *         )
 *     ),
 *     @OA\Response(response=422, description="Invitation invalid or expired")
 * )
 */
class Accept {}