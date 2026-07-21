<?php

namespace App\Http\Controllers\Api;

use App\Model\Booking;
use App\Model\ClubTable;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Bookings", description: "API Endpoints for Client Bookings")]
class ClientBookingController extends Controller
{
    #[OA\Get(
        path: "/api/bookings",
        summary: "List authenticated client bookings",
        tags: ["Bookings"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of bookings",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(Request $request)
    {
        $bookings = $request->user()->bookings()
            ->with(['table', 'event'])
            ->latest()
            ->get();

        return response()->json($bookings);
    }

    #[OA\Get(
        path: "/api/bookings/{id}",
        summary: "Get booking details",
        tags: ["Bookings"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "Booking ID", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Booking details",
                content: new OA\JsonContent(type: "object")
            ),
            new OA\Response(response: 404, description: "Booking not found"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function show(Request $request, $id)
    {
        $booking = $request->user()->bookings()
            ->with(['table', 'event', 'guests'])
            ->findOrFail($id);

        return response()->json($booking);
    }

    #[OA\Post(
        path: "/api/bookings",
        summary: "Create a new booking request",
        tags: ["Bookings"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["booking_date", "start_time", "end_time", "guest_count"],
                properties: [
                    new OA\Property(property: "table_id", type: "integer", nullable: true, example: 1),
                    new OA\Property(property: "event_id", type: "integer", nullable: true, example: 2),
                    new OA\Property(property: "booking_date", type: "string", format: "date", example: "2026-08-01"),
                    new OA\Property(property: "start_time", type: "string", example: "19:00"),
                    new OA\Property(property: "end_time", type: "string", example: "22:00"),
                    new OA\Property(property: "guest_count", type: "integer", example: 4),
                    new OA\Property(property: "special_requests", type: "string", example: "Window side table"),
                    new OA\Property(
                        property: "guests",
                        type: "array",
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "name", type: "string", example: "Jane Doe"),
                                new OA\Property(property: "email", type: "string", format: "email", example: "jane@example.com"),
                                new OA\Property(property: "phone", type: "string", example: "+1234567890")
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Booking request created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "booking", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation error or table occupied"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'nullable|exists:tables,id',
            'event_id' => 'nullable|exists:events,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required',
            'guest_count' => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
            'guests' => 'nullable|array',
            'guests.*.name' => 'required|string',
            'guests.*.email' => 'nullable|email',
            'guests.*.phone' => 'nullable|string',
        ]);

        // Check if table is available
        if (!empty($validated['table_id'])) {
            $isOccupied = Booking::where('table_id', $validated['table_id'])
                ->where('booking_date', $validated['booking_date'])
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                          ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']]);
                })
                ->exists();

            if ($isOccupied) {
                return response()->json(['message' => 'Selected table is occupied for the requested slot.'], 422);
            }
        }

        // Generate dynamic QR code payload
        $bookingCode = 'IMP-' . strtoupper(uniqid());

        $booking = $request->user()->bookings()->create(array_merge($validated, [
            'status' => 'pending',
            'qr_code' => $bookingCode
        ]));

        if (!empty($validated['guests'])) {
            foreach ($validated['guests'] as $guest) {
                $booking->guests()->create($guest);
            }
        }

        return response()->json([
            'message' => 'Booking request submitted successfully.',
            'booking' => $booking->load('guests')
        ], 201);
    }

    #[OA\Post(
        path: "/api/bookings/{id}/cancel",
        summary: "Cancel a booking",
        tags: ["Bookings"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "Booking ID", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Booking cancelled successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Booking cancelled successfully.")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Checked in bookings cannot be cancelled"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function cancel(Request $request, $id)
    {
        $booking = $request->user()->bookings()->findOrFail($id);

        if ($booking->status === 'checked_in') {
            return response()->json(['message' => 'Checked in bookings cannot be cancelled.'], 422);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Booking cancelled successfully.']);
    }
}
