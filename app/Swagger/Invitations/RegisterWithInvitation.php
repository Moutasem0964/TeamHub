<?php

namespace App\Swagger\Invitations;

/**
 * @OA\Post(
 *     path="/api/v1/register-with-invitation",
 *     summary="Register a new user with an invitation",
 *     description="Registers a new user who has accepted an invitation but does not yet have an account.  
 *                  The server ensures the invitation is valid and binds the new user to the tenant with the invited role.",
 *     tags={"Tenant Invitations"},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"invitation_token","name","password","password_confirmation","invitaion_id"},
 *             @OA\Property(property="invitation_token", type="string", example="raw-token-from-link"),
 *             @OA\Property(property="invitation_id", type="string", example="raw-id-from-link"),
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="password", type="string", format="password", example="secret123"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", example="secret123")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Registration successful, tenant joined",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="registration successful, tenant joined"),
 *             @OA\Property(property="user", ref="#/components/schemas/User"),
 *             @OA\Property(
 *                 property="tenant",
 *                 type="object",
 *                 @OA\Property(property="id", type="string", format="uuid"),
 *                 @OA\Property(property="role", type="string", example="member")
 *             ),
 *             @OA\Property(property="token", type="string", example="Bearer eyJ0eXAiOiJKV...")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Invalid or expired invitation token",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid or expired token.")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error (e.g., password mismatch)",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The password confirmation does not match.")
 *         )
 *     )
 * )
 */

class RegisterWithInvitation{}