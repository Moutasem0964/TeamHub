<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Tenant",
 *     type="object",
 *     title="Tenant",
 *     required={"id","name","slug","plan"},
 *     @OA\Property(property="id", type="string", format="uuid", example="31421031-172e-440f-8f8a-4894a2d09de8"),
 *     @OA\Property(property="name", type="string", example="Tenan2"),
 *     @OA\Property(property="slug", type="string", example="tenan2"),
 *     @OA\Property(property="plan", type="string", example="free"),
 *     @OA\Property(property="settings", type="object", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-29T15:33:11.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-29T15:33:11.000000Z"),
 *     @OA\Property(
 *         property="pivot",
 *         type="object",
 *         @OA\Property(property="user_id", type="string", format="uuid", example="4b928c84-4d52-4745-9920-9d8d8a28df49"),
 *         @OA\Property(property="tenant_id", type="string", format="uuid", example="31421031-172e-440f-8f8a-4894a2d09de8"),
 *         @OA\Property(property="role", type="string", example="member")
 *     )
 * )
 */
class Tenant {}