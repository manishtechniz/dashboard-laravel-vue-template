<?php

namespace App\Http\Controllers\Api;

use App\Model\Complaint;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Complaints", description: "API Endpoints for Client Complaints")]
class ClientComplaintController extends Controller
{
    #[OA\Get(
        path: "/api/complaints",
        summary: "List complaints submitted by the client",
        tags: ["Complaints"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of complaints",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(Request $request)
    {
        $complaints = $request->user()->complaints()
            ->with('club:id,name')
            ->latest('id')
            ->paginate();

        return response()->json([
            'data' => $complaints
        ]);
    }

    #[OA\Post(
        path: "/api/complaints",
        summary: "Submit a new complaint",
        tags: ["Complaints"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["message", "club_id"],
                properties: [
                    new OA\Property(property: "booking_id", type: "integer", nullable: true, example: 1),
                    new OA\Property(property: "remark", type: "string", nullable: true, example: "you can put some text which from added"),
                    new OA\Property(property: "club_id", type: "integer", nullable: true, example: 1),
                    new OA\Property(property: "message", type: "string", example: "The service at the VIP section was extremely slow.")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Complaint submitted successfully",
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
            'message' => 'required|string|max:2000',
            'remark' => 'nullable|string|max:200',
        ]);

        $complaint = $request->user()->complaints()->create($validated);

        return response()->json([
            'message' => 'Complaint submitted successfully.',
            'complaint' => $complaint->load('club:id,name')
        ], 201);
    }
}
