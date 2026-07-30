<?php

namespace App\Http\Controllers\Api;

use App\Model\Review;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Reviews", description: "API Endpoints for Club Reviews")]
class ClientReviewController extends Controller
{
    #[OA\Get(
        path: "/api/reviews",
        summary: "List all reviews",
        tags: ["Reviews"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "club_id", in: "query", required: false, description: "Filter by Club ID", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of reviews",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(Request $request)
    {
        $validated = $request->validate([
            'club_id' => 'nullable|exists:clubs,id'
        ]);

        $clubId = $validated['club_id'] ?? null;

        $query = Review::with(['client:id,name', 'club:id,name']);

        if ($clubId) {
            $query->where('club_id', $clubId);
        }

        $reviews = $query->latest()->with(['club:id,name', 'client:id,name'])->paginate();

        return response()->json([
            'data' => $reviews
        ]);
    }

    #[OA\Post(
        path: "/api/reviews",
        summary: "Create a review for a club",
        tags: ["Reviews"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["rating", "club_id"],
                properties: [
                    new OA\Property(property: "club_id", type: "integer", nullable: true, example: 1),
                    new OA\Property(property: "booking_id", type: "integer", nullable: true, example: 1),
                    new OA\Property(property: "is_anonymous", type: "integer", nullable: true, example: 0 / 1),
                    new OA\Property(property: "remark", type: "string", nullable: true, example: "you can put some text which from added"),
                    new OA\Property(property: "rating", type: "integer", example: 5, minimum: 1, maximum: 5),
                    new OA\Property(property: "comment", type: "string", example: "Amazing place, great music!")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Review created successfully",
                content: new OA\JsonContent(type: "object")
            ),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
            'remark' => 'nullable|string|max:300',
            'is_anonymous' => 'nullable|in:0,1',
        ]);

        $review = $request->user()->reviews()->create($validated);

        return response()->json([
            'message' => 'Review created successfully.',
            'review' => $review->load(['client:id,name', 'club:id,name'])
        ], 201);
    }
}
