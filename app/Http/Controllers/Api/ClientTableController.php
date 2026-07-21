<?php

namespace App\Http\Controllers\Api;

use App\Model\ClubTable;
use App\Model\Floor;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Tables", description: "API Endpoints for Table & Floor Availability")]
class ClientTableController extends Controller
{
    #[OA\Get(
        path: "/api/tables/floors",
        summary: "List active floors with available tables",
        tags: ["Tables"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of active floors and available tables",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(Request $request)
    {
        $floors = Floor::with(['tables' => function ($query) {
            $query->where('status', 'available');
        }])
        ->where('is_active', true)
        ->get();

        return response()->json($floors);
    }

    #[OA\Get(
        path: "/api/tables/available",
        summary: "Check table availability by date, time slot, and capacity",
        tags: ["Tables"],
        parameters: [
            new OA\Parameter(name: "booking_date", in: "query", required: true, description: "Booking Date (YYYY-MM-DD)", schema: new OA\Schema(type: "string", format: "date", example: "2026-08-01")),
            new OA\Parameter(name: "start_time", in: "query", required: true, description: "Start Time (HH:MM)", schema: new OA\Schema(type: "string", example: "19:00")),
            new OA\Parameter(name: "end_time", in: "query", required: true, description: "End Time (HH:MM)", schema: new OA\Schema(type: "string", example: "22:00")),
            new OA\Parameter(name: "capacity", in: "query", required: false, description: "Minimum table guest capacity", schema: new OA\Schema(type: "integer", example: 4))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of available tables",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            ),
            new OA\Response(response: 422, description: "Validation errors")
        ]
    )]
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'capacity' => 'nullable|integer'
        ]);

        $tables = ClubTable::where('status', 'available');
        if (!empty($validated['capacity'])) {
            $tables->where('capacity', '>=', $validated['capacity']);
        }
        $tables = $tables->get();

        return response()->json($tables);
    }
}
