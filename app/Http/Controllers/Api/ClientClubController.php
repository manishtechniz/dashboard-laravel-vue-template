<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Club as ModelClub;
use App\Model\Club;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Clubs", description: "API Endpoints for Clubs")]
class ClientClubController extends Controller
{
    #[OA\Get(
        path: "/api/clubs",
        summary: "Get list of clubs",
        tags: ["Clubs"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Clubs retrieved successfully"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "current_page", type: "integer", example: 1),
                                new OA\Property(
                                    property: "data",
                                    type: "array",
                                    items: new OA\Items(
                                        type: "object",
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "name", type: "string", example: "Club Name"),
                                            new OA\Property(property: "is_active", type: "boolean", example: true),
                                            new OA\Property(property: "average_rating", type: "number", format: "float", example: 4.5),
                                            new OA\Property(property: "review_count", type: "integer", example: 10)
                                        ]
                                    )
                                ),
                                new OA\Property(property: "first_page_url", type: "string", example: "http://localhost/api/clubs?page=1"),
                                new OA\Property(property: "from", type: "integer", example: 1),
                                new OA\Property(property: "last_page", type: "integer", example: 1),
                                new OA\Property(property: "last_page_url", type: "string", example: "http://localhost/api/clubs?page=1"),
                                new OA\Property(property: "next_page_url", type: "string", nullable: true, example: null),
                                new OA\Property(property: "path", type: "string", example: "http://localhost/api/clubs"),
                                new OA\Property(property: "per_page", type: "integer", example: 15),
                                new OA\Property(property: "prev_page_url", type: "string", nullable: true, example: null),
                                new OA\Property(property: "to", type: "integer", example: 1),
                                new OA\Property(property: "total", type: "integer", example: 1)
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(): JsonResponse
    {
        $clubs = ModelClub::where('is_active', true)->paginate();

        return response()->json([
            'message' => 'Clubs retrieved successfully',
            'data' => $clubs
        ]);
    }

    #[OA\Get(
        path: "/api/clubs/{id}",
        summary: "Get a specific club by ID",
        tags: ["Clubs"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "ID of club to return",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "status", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Club retrieved successfully"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "Club Name"),
                                new OA\Property(property: "is_active", type: "boolean", example: true)
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 404, description: "Club not found")
        ]
    )]
    public function show($id): JsonResponse
    {
        $club = Club::where('is_active', true)->find($id);

        if (empty($club)) {
            return response()->json([
                'message' => 'Record not found.',
            ], 404);
        }

        return response()->json([
            'message' => 'Club retrieved successfully',
            'data' => $club
        ]);
    }
}
