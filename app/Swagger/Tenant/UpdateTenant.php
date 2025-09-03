<?php

namespace App\Swagger\Tenant;

/**
 * @OA\Patch(
 *     path="/api/v1/tenants/{tenant}",
 *     summary="Partially update a tenant",
 *     description="Update one or more tenant fields such as name, plan, or settings. Only owners and admins can update a tenant.",
 *     operationId="patchTenant",
 *     tags={"Tenants"},
 *     security={{"sanctum":{}}},
 *
 *     @OA\Parameter(
 *         name="tenant",
 *         in="path",
 *         description="Tenant UUID",
 *         required=true,
 *         @OA\Schema(type="string", format="uuid")
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="Tenant1.Inc"),
 *             @OA\Property(property="plan", type="string", example="free"),
 *             @OA\Property(
 *                 property="settings",
 *                 type="object",
 *                 @OA\Property(property="theme", type="string", example="light"),
 *                 @OA\Property(property="timezone", type="string", example="UTC"),
 *                 @OA\Property(property="locale", type="string", example="en"),
 *                 @OA\Property(
 *                     property="notifications",
 *                     type="object",
 *                     @OA\Property(property="email", type="boolean", example=true),
 *                     @OA\Property(property="sms", type="boolean", example=false)
 *                 ),
 *                 @OA\Property(
 *                     property="branding",
 *                     type="object",
 *                     @OA\Property(property="logo_url", type="string", nullable=true, example=null),
 *                     @OA\Property(property="primary_color", type="string", example="#2563eb")
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Tenant updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Tenant updated successfully."),
 *             @OA\Property(
 *                 property="tenant",
 *                 type="object",
 *                 ref="#/components/schemas/Tenant"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Unauthorized - you are not allowed to update this tenant"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 */
class UpdateTenant {}
