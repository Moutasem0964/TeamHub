<?php

namespace App\Swagger\Invitations;

/**
 * @OA\Post(
 *     path="/api/v1/tenants/invite",
 *     summary="Send a tenant invitation",
 *     tags={"Tenant Invitations"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","role"},
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="role", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Invitation sent successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(
 *                 property="invitation",
 *                 type="object",
 *                 @OA\Property(property="id", type="string"),
 *                 @OA\Property(property="email", type="string"),
 *                 @OA\Property(property="role", type="string"),
 *                 @OA\Property(property="expires_at", type="string", format="date-time")
 *             )
 *         )
 *     )
 * )
 */
class Send {}