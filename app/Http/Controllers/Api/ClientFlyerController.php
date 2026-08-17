<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Flyer;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Flyers", description: "API Endpoints for Flyers")]
class ClientFlyerController extends Controller
{
    #[OA\Get(
        path: "/api/flyers",
        summary: "Get list of active flyers",
        tags: ["Flyers"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                type: "object",
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "file_name", type: "string", example: "flyer1.jpg"),
                                    new OA\Property(property: "file_type", type: "string", example: "image"),
                                    new OA\Property(property: "file_path", type: "string", example: "flyers/flyer1.jpg"),
                                    new OA\Property(property: "audio_file", type: "string", nullable: true, example: "audio/music.mp3"),
                                    new OA\Property(property: "is_active", type: "boolean", example: true),
                                    new OA\Property(property: "file_url", type: "string", example: "http://localhost/storage/flyers/flyer1.jpg"),
                                    new OA\Property(property: "audio_url", type: "string", nullable: true, example: "http://localhost/storage/audio/music.mp3")
                                ]
                            )
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(): JsonResponse
    {
        $flyers = Flyer::where('is_active', true)
            ->latest()
            ->paginate();

        return response()->json([
            'status' => 'success',
            'data' => $flyers
        ]);
    }
}
