<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Booking;
use App\Model\ClubTable;
use Illuminate\Http\Request;

class ClientBookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = $request->user()->bookings()
            ->with(['table', 'event'])
            ->latest()
            ->get();

        return response()->json($bookings);
    }

    public function show(Request $request, $id)
    {
        $booking = $request->user()->bookings()
            ->with(['table', 'event', 'guests'])
            ->findOrFail($id);

        return response()->json($booking);
    }

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
        if ($validated['table_id']) {
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
