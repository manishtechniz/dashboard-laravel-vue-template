<?php

namespace App\Http\Controllers\Api;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Client API",
    description: "API documentation for Imperial Client Mobile & Web Applications",
    contact: new OA\Contact(email: "support@imperial.com")
)]
#[OA\Server(
    url: L5_SWAGGER_CONST_HOST,
    description: "API Server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "Bearer",
    description: "Enter your Sanctum Bearer token"
)]
abstract class Controller
{
    //
}
