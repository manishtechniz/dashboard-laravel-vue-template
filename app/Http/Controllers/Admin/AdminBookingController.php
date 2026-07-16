<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\BookingDataGrid;
use App\Model\Booking;
use App\Model\BookingGuest;
use App\Model\Client;
use App\Model\ClubTable;
use App\Model\Event;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(BookingDataGrid::class)->process();
        }

        $clients = Client::all();
        $tables = ClubTable::all();
        $events = Event::all();

        return view('admin::bookings.index', compact('clients', 'tables', 'events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'table_id' => 'nullable|exists:tables,id',
            'event_id' => 'nullable|exists:events,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'guest_count' => 'required|integer|min:1',
            'status' => 'required|string|in:pending,confirmed,cancelled,checked_in',
            'special_requests' => 'nullable|string',
            'guests' => 'nullable|array',
            'guests.*.name' => 'required|string',
            'guests.*.email' => 'nullable|email',
            'guests.*.phone' => 'nullable|string',
        ]);

        $booking = Booking::create($validated);

        if (!empty($validated['guests'])) {
            foreach ($validated['guests'] as $guestData) {
                $booking->guests()->create($guestData);
            }
        }

        return response()->json(['message' => 'Booking created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'table_id' => 'nullable|exists:tables,id',
            'event_id' => 'nullable|exists:events,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'guest_count' => 'required|integer|min:1',
            'status' => 'required|string|in:pending,confirmed,cancelled,checked_in',
            'special_requests' => 'nullable|string',
        ]);

        $booking->update($validated);

        return response()->json(['message' => 'Booking updated successfully.']);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully.']);
    }
}
