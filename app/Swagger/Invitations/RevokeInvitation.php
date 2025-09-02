<?php

namespace App\Swagger\Invitations;


/**
 * @OA\Delete(
 *     path="/api/v1/tenants/delete-invitation/{invitation}",
 *     operationId="revokeInvitation",
 *     tags={"Tenant Invitations"},
 *     summary="Revoke a tenant invitation",
 *     description="Marks a tenant invitation as revoked.",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="invitation",
 *         in="path",
 *         description="UUID of the invitation to revoke",
 *         required=true,
 *         @OA\Schema(type="string", format="uuid")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Invitation revoked successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Invitation revoked successfully.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="This invitation cannot be revoked",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="This invitation cannot be revoked.")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=403, description="Forbidden")
 * )
 */
class RevokeInvitation {}
