<?php

namespace App\Swagger\Invitations;

/**
 * @OA\Get(
 *     path="/api/v1/tenants/invitations/accept/{invitation}/{token}",
 *     summary="Accept a tenant invitation",
 *     tags={"Tenant Invitations"},
 *     @OA\Parameter(
 *         name="invitation",
 *         in="path",
 *         required=true,
 *         description="Invitation ID",
 *         @OA\Schema(type="string", format="uuid")
 *     ),
 *     @OA\Parameter(
 *         name="token",
 *         in="path",
 *         required=true,
 *         description="Invitation token",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Invitation accepted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Invitation accepted successfully. Please Login!"),
 *             @OA\Property(property="user_id", type="string", format="uuid"),
 *             @OA\Property(property="tenant_id", type="string", format="uuid"),
 *             @OA\Property(property="role", type="string", format="string",example="member"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=202,
 *         description="Registration required (user not found)",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="requires_registration", type="boolean", example=true),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="tenant_id", type="string", format="uuid"),
 *             @OA\Property(property="role", type="string", example="member"),
 *             @OA\Property(property="invitation_id", type="string", format="uuid"),
 *             @OA\Property(property="invitation_token", type="string")
 *         )
 *     ),
 *     @OA\Response(response=403, description="Invalid or expired token"),
 *     @OA\Response(response=422, description="Invitation no longer valid")
 * )
 */
class Accept {}