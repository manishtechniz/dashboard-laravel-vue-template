<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\BookingDataGrid;
use App\Jobs\SendFirebaseNotificationJob;
use App\Model\Booking;
use App\Model\BookingGuest;
use App\Model\Client;
use App\Model\ClubTable;
use App\Model\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'guest_count' => 'required|integer|min:0',
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
            'guest_count' => 'required|integer|min:0',
            'status' => 'required|string|in:pending,confirmed,cancelled,checked_in',
            'special_requests' => 'nullable|string',
        ]);

        try {
            $beforeBookingStatus = $booking->status?->value;

            $booking->update($validated);

            if (! empty($validated['status']) && $beforeBookingStatus != $validated['status']) {
                $bookingClient = $booking->client;

                // Dispatch to a single token
                SendFirebaseNotificationJob::dispatch(
                    'token',
                    $bookingClient?->fcm_token,
                    $booking->status?->notificationTitle() . '#' . $booking->id,
                    $booking->status?->notificationDescription(),
                    [
                        'type' => 'booking_status',
                        'created_by' => Auth::guard('admin')->id(),
                        'remark' => "from admin",
                        'additional' => [
                            'screen' => 'booking',
                            'booking_id' => $booking->id,
                            'client_id' => $booking->client_id
                        ]
                    ]
                );
            }
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            return response()->json([
                'message' => 'Encounter error during update.',
            ], 422);
        }

        return response()->json(['message' => 'Booking updated successfully.']);
    }

    public function massStatus(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
            'value' => 'required|string|in:pending,confirmed,cancelled,checked_in',
        ]);

        Booking::whereIn('id', $validated['indices'])->update(['status' => $validated['value']]);

        return response()->json(['message' => 'Bookings status updated successfully.']);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully.']);
    }

    public function guests($id)
    {
        $guests = BookingGuest::where('booking_id', $id)->get();

        return response()->json([
            'guests' => $guests
        ]);
    }

    public function massDestroy(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
        ]);

        Booking::whereIn('id', $validated['indices'])->delete();

        return response()->json(['message' => 'Bookings deleted successfully.']);
    }
}
