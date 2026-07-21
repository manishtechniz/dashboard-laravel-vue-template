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
        $events = Event::with('club')
            ->where('end_time', '>=', now())
            ->orderBy('start_time')
            ->get();

        return response()->json($events);
    }

    #[OA\Get(
        path: "/api/events/{id}",
        summary: "Get details of a specific event",
        tags: ["Events"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "Event ID", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Event details",
                content: new OA\JsonContent(type: "object")
            ),
            new OA\Response(response: 404, description: "Event not found")
        ]
    )]
    public function show($id)
    {
        $event = Event::with('club')->findOrFail($id);

        return response()->json($event);
    }
}
