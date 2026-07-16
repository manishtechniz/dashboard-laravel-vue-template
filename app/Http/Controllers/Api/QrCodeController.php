<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Booking;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    public function show($bookingCode)
    {
        $booking = Booking::where('qr_code', $bookingCode)
            ->with(['client', 'table', 'event'])
            ->firstOrFail();

        return response()->json([
            'qr_code' => $booking->qr_code,
            'client_name' => $booking->client->name,
            'table' => $booking->table ? $booking->table->name : 'N/A',
            'event' => $booking->event ? $booking->event->name : 'N/A',
            'booking_date' => $booking->booking_date,
            'status' => $booking->status
        ]);
    }

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
