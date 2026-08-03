<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Imperial API Documentation",
     *      description="L5 Swagger OpenApi description",
     *      @OA\Contact(
     *          email="admin@example.com"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="API Server"
     * )
     */
}
