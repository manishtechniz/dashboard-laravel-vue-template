<?php

namespace App\Http\Controllers\Api;

use App\Model\FeatureRequest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Feature Requests", description: "API Endpoints for Client Feature Requests")]
class ClientFeatureRequestController extends Controller
{
    #[OA\Get(
        path: "/api/feature-requests",
        summary: "List feature requests submitted by the client",
        tags: ["Feature Requests"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of feature requests",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(Request $request)
    {
        $featureRequests = $request->user()->featureRequests()
            ->latest()
            ->with(['client:id,name'])
            ->paginate('id');

        return response()->json([
            'data' => $featureRequests
        ]);
    }

    #[OA\Post(
        path: "/api/feature-requests",
        summary: "Submit a new feature request",
        tags: ["Feature Requests"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title", "description"],
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Integrate Apple Pay"),
                    new OA\Property(property: "description", type: "string", example: "It would be great to be able to pay for bookings using Apple Pay directly in the app.")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Feature request submitted successfully",
                content: new OA\JsonContent(type: "object")
            ),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $featureRequest = $request->user()->featureRequests()->create(array_merge($validated, [
            'status' => 'pending',
            'priority' => 'medium'
        ]));

        return response()->json([
            'message' => 'Feature request submitted successfully.',
            'feature_request' => $featureRequest->load('client:id,name'),
        ], 201);
    }
}
