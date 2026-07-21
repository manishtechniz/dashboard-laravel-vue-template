<?php

namespace App\Http\Controllers\Api;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Imperial Client API",
    description: "API documentation for Imperial Client Mobile & Web Applications",
    contact: new OA\Contact(email: "support@imperial.com")
)]
#[OA\Server(
    url: "/",
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
