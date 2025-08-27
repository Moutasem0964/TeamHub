<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="TeamHub API",
 *     description="API documentation for TeamHub project",
 *     @OA\Contact(
 *         email="support@teamhub.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API server"
 * )
 */
class Swagger {}
