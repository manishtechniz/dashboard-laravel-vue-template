<?php

namespace App\Http\Controllers\Api;

use App\Model\Booking;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "QR Code", description: "API Endpoints for QR Code Display & Validation/Check-in")]
class QrCodeController extends Controller
{
    #[OA\Get(
        path: "/api/qrcode/{code}",
        summary: "View booking details by QR code",
        tags: ["QR Code"],
        parameters: [
            new OA\Parameter(name: "code", in: "path", required: true, description: "Unique QR Code string", schema: new OA\Schema(type: "string", example: "IMP-66A1B2C3"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Booking details associated with QR code",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "qr_code", type: "string"),
                        new OA\Property(property: "client_name", type: "string"),
                        new OA\Property(property: "table", type: "string"),
                        new OA\Property(property: "event", type: "string"),
                        new OA\Property(property: "booking_date", type: "string"),
                        new OA\Property(property: "status", type: "string")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Booking not found for QR code")
        ]
    )]
    public function show($bookingCode)
    {
        $booking = Booking::where('qr_code', $bookingCode)
            ->with(['client', 'table', 'event'])
            ->firstOrFail();

        return response()->json([
            'qr_code' => $booking->qr_code,
            'client_name' => $booking->client ? $booking->client->name : 'N/A',
            'table' => $booking->table ? $booking->table->name : 'N/A',
            'event' => $booking->event ? $booking->event->name : 'N/A',
            'booking_date' => $booking->booking_date,
            'status' => $booking->status
        ]);
    }

    #[OA\Post(
        path: "/api/qrcode/validate",
        summary: "Validate QR code and check in booking",
        tags: ["QR Code"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["qr_code"],
                properties: [
                    new OA\Property(property: "qr_code", type: "string", example: "IMP-66A1B2C3")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Booking checked in successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Booking checked in successfully."),
                        new OA\Property(property: "booking", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Invalid QR code"),
            new OA\Response(response: 422, description: "Booking already checked in or cancelled"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function validateCode(Request $request)
    {
        $validated = $request->validate([
            'qr_code' => 'required|string',
        ]);

        $booking = Booking::where('qr_code', $validated['qr_code'])->first();

        if (!$booking) {
            return response()->json(['message' => 'Invalid QR Code.'], 404);
        }

        if ($booking->status === 'cancelled') {
            return response()->json(['message' => 'Booking was cancelled.'], 422);
        }

        if ($booking->status === 'checked_in') {
            return response()->json(['message' => 'Booking already checked in.'], 422);
        }

        $booking->update(['status' => 'checked_in']);

        return response()->json([
            'message' => 'Booking checked in successfully.',
            'booking' => $booking
        ]);
    }
}
