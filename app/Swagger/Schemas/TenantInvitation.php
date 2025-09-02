<?php

namespace App\Swagger\Schemas;


/**
 * @OA\Schema(
 *     schema="TenantInvitation",
 *     type="object",
 *     title="Tenant Invitation",
 *     description="A tenant invitation object",
 *     @OA\Property(property="id", type="string", format="uuid", example="a4de3245-70ed-484d-9430-90b77eb897cd"),
 *     @OA\Property(property="email", type="string", format="email", example="example@gmail.com"),
 *     @OA\Property(property="role", type="string", example="member"),
 *     @OA\Property(property="expires_at", type="string", format="date-time", example="2025-09-05T15:17:51.000000Z"),
 *     @OA\Property(property="accepted_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="revoked", type="boolean", example=false),
 * )
 */
class TenantInvitation {}
