<?php

namespace App\Http\Controllers\Api;

use App\Model\Event;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Events", description: "API Endpoints for Club Events")]
class ClientEventController extends Controller
{
    #[OA\Get(
        path: "/api/events",
        summary: "List upcoming club events",
        tags: ["Events"],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of upcoming events",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            )
        ]
    )]
    public function index()
    {
        try {
            $events = Event::with(['club:id,name', 'coupon:event_id,label,code,description'])
                ->paginate();

            return response()->json([
                'data' => $events
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Error fetching events',
            ], 500);
        }
    }
}
