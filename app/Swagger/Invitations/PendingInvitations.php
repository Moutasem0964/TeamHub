<?php

namespace App\Swagger\Invitations;

/**
 * @OA\Get(
 *     path="/api/v1/tenants/pending-invitations",
 *     operationId="getPendingInvitations",
 *     tags={"Tenant Invitations"},
 *     summary="Get all pending tenant invitations for current tenant",
 *     description="Returns a list of pending invitations filtered by tenant.",
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Pending invitations retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Pending invitations retrieved successfully."),
 *             @OA\Property(
 *                 property="invitations",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/TenantInvitation")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */
class PendingInvitations {}
