<?php

namespace App\Http\Controllers\Api;

use App\Enums\BookingStatus;
use App\Model\Booking;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "QR Code", description: "API Endpoints for QR Code Display & Validation/Check-in")]
class QrCodeController extends Controller
{
    #[OA\Get(
        path: "/api/qrcode/scan",
        summary: "View booking details by QR code",
        security: [["bearerAuth" => []]],
        tags: ["QR Code"],
        parameters: [
            new OA\Parameter(
                name: "qr_code_id",
                in: "query",
                required: false,
                description: "",
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Booking details associated with QR code",
                content: new OA\JsonContent(
                    type: "object"
                )
            ),
            new OA\Response(response: 404, description: "Booking not found for QR code")
        ]
    )]
    public function show(Request $request)
    {
        $validated = $request->validate([
            'qr_code_id' => 'required',
        ]);

        if (! $request->user()->hasAppPermission('can_qr_scan')) {
            return response()->json(['message' => 'You do not have permission to perform this action.'], 403);
        }

        $booking = Booking::where('qr_code', $validated['qr_code_id'])
            ->with(['table:id,name', 'club:id,name', 'guests', 'event:id,name'])
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Invalid QR Code.'], 404);
        }

        return response()->json([
            'booking' => $booking
        ]);
    }

    #[OA\Post(
        path: "/api/qrcode/booking-checkin",
        summary: "Validate QR code and check in booking",
        tags: ["QR Code"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["qr_code_id"],
                properties: [
                    new OA\Property(property: "qr_code_id", type: "string", example: "XXXXXXXX")
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
            new OA\Response(response: 422, description: "Invalid QR code or Booking already checked in or cancelled"),
            new OA\Response(response: 403, description: "Unauthenticated")
        ]
    )]
    public function checkIn(Request $request)
    {
        $validated = $request->validate([
            'qr_code_id' => 'required|string',
        ]);

        if (! $request->user()->hasAppPermission('can_booking_check_in')) {
            return response()->json(['message' => 'You do not have permission to perform this action.'], 403);
        }

        $booking = Booking::where('qr_code', $request['qr_code_id'])->first();

        if (! $booking) {
            return response()->json(['message' => 'Invalid QR Code.'], 422);
        }

        if ($booking->status === BookingStatus::CANCELLED) {
            return response()->json(['message' => 'Booking was cancelled.'], 422);
        }

        if ($booking->status === BookingStatus::CHECKED_IN) {
            return response()->json(['message' => 'Booking already checked in.'], 422);
        }

        $booking->update(['status' => 'checked_in']);

        return response()->json([
            'message' => 'Booking checked in successfully.',
        ]);
    }
}
