<?php

namespace App\Http\Controllers\Api;

use App\Model\ClubTable;
use App\Model\Floor;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Tables", description: "API Endpoints for Table")]
class ClientTableController extends Controller
{
    #[OA\Get(
        path: "/api/tables",
        summary: "List active tables",
        tags: ["Tables"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "booking_date",
                in: "query",
                required: true,
                schema: new OA\Schema(type: "string", format: "date", example: "2026-07-26")
            ),
            new OA\Parameter(
                name: "club_id",
                in: "query",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of active tables",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(Request $request)
    {
        $validated = $request->validate([
            'booking_date' => 'required|date_format:Y-m-d',
            'club_id'      => 'required',
        ]);

        try {
            //code... 
            $bookingDate = $validated['booking_date'] ?? now()->toDateString();
            $clubId = $validated['club_id'];

            $tables = ClubTable::where([
                'status' => 'active',
                'club_id' => $clubId,
            ])
                ->with(['bookings' => function ($query) use ($bookingDate) {
                    $query->whereDate('booking_date', $bookingDate)
                        ->where('status', '!=', 'cancelled');
                }])
                ->get();

            $tablesData = $tables->map(function ($table) {
                $totalBookings = $table->bookings->count();
                $remainVipTable = max(0, $table->total_tables - $totalBookings);
                $isAvailable = $remainVipTable > 0;

                $tableArray = $table->toArray();
                $tableArray['remain_table'] = $remainVipTable;
                $tableArray['is_avaliable'] = $isAvailable;

                unset($tableArray['bookings']);

                return $tableArray;
            });

            return response()->json([
                'data' => $tablesData
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Encountered error during fetch tables.'
            ], 500);
        }
    }
}
