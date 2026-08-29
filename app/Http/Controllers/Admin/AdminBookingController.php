<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\BookingDataGrid;
use App\Jobs\SendFirebaseNotificationJob;
use App\Model\Booking;
use App\Model\BookingGuest;
use App\Model\Client;
use App\Model\ClientBalance;
use App\Model\ClubTable;
use App\Model\Event;
use App\Services\ClientBalanceService;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminBookingController extends Controller
{
    public function __construct(
        private CouponService $couponService,
        private ClientBalanceService $clientBalanceService,
    ) {}

    public function index()
    {
        if (request()->ajax()) {
            return datagrid(BookingDataGrid::class)->process();
        }

        $clients = Client::all();
        $tables = ClubTable::all();
        $events = Event::all();
        $promoCodes = \App\Model\PromoCode::where('is_active', true)->get();

        return view('admin::bookings.index', compact('clients', 'tables', 'events', 'promoCodes'));
    }

    public function store(Request $request)
    {
        return response()->json([
            'message' => 'Sorry!, You can not create booking'
        ], 500);

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
            'base_price' => 'nullable|numeric|min:0',
            'discount_code' => 'nullable|string',
        ]);

        $this->calculateBookingAmounts($validated);

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
            'base_price' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'discount_code' => 'nullable|string',
        ]);

        // dd($request->all());

        DB::beginTransaction();

        try {
            $adminInfo = Auth::guard('admin')->user();
            $couponCode = $validated['discount_code'] ?? null;
            $basePrice = (float) $validated['base_price'] ?? 0;
            $paidAmount = (float) $validated['paid_amount'] ?? 0;
            $discount = 0;
            $discountType =  null;
            $maxDiscountAmount = 0;
            $discountSource = null;
            $discountNote = null;
            $clientId = $validated['client_id'];

            $booking = Booking::lockForUpdate()->findOrFail($id);

            /**
             * Coupon functionality
             * 
             * NOTE:: if coupon is `INVALID_COUPON` that may booking has this coupon but does not exists in coupon table
             */
            if (strtoupper($couponCode) != 'NONE' && ! empty($couponCode)) {
                $couponService = $this->couponService->apply($couponCode, $basePrice, [
                    'event_id' => $eventId ?? null,
                    'booking_date' => $bookingDate ?? null,
                    'club_id' => $clubId ?? null,
                ]);

                // Return all errors except coupon does not exits.
                if (! $couponService['status'] && ($couponService['type'] ?? null) != 'INVALID_COUPON') {
                    return response()->json($couponService, 422);

                    // Verify that non exits coupon attach with booking or not
                } else if (($couponService['type'] ?? null) == 'INVALID_COUPON'
                    && $booking->discount_code != $couponCode
                ) {
                    return response()->json(create422ErrorFormat('discount_code', 'Invalid coupon code found.'), 422);
                }

                // Old coupon
                if (($couponService['type'] ?? null) == 'INVALID_COUPON') {
                    $discount = $booking->discount_amount;

                    // new coupon
                } else {
                    $discount = $couponService['discount'];
                    $discountType = $couponService['discount_type'];
                    $maxDiscountAmount = $couponService['max_discount_amount'];
                    $discountSource = 'Admin dashboard';
                    $discountNote = 'created by admin #' . $adminInfo?->id . ' and name is ' . $adminInfo?->name;

                    $validated =  array_merge($validated, [
                        'discount_type' => $discountType,
                        'discount_code' => $couponCode,
                        'discount_source' => $discountSource,
                        'discount_note' => $discountNote,
                        'discount_amount' => $discount,
                        'max_discount_amount' => $maxDiscountAmount,
                    ]);

                    $couponService['instance']->increment('used_count');
                    $couponService['instance']->save();
                }
            } else {
                // Reset coupon
                $validated =  array_merge($validated, [
                    'discount_type' => null,
                    'discount_code' => null,
                    'discount_source' => null,
                    'discount_note' => empty($couponCode) ? null : 'delete from admin dashboard by #' . $adminInfo?->id,
                    'discount_amount' => 0,
                    'max_discount_amount' => null,
                ]);
            }

            $taxRate = 0;
            $totalAmountExclTax = $basePrice - $discount;
            $taxAmount = ($totalAmountExclTax * $taxRate) / 100;
            $totalAmountInclTax = $totalAmountExclTax + $taxAmount;

            // 1. IDENTIFY OLD AMOUNTS (To be revoked/adjusted)
            $oldTotalAmount = $booking->total_amount_incl_tax ?? 0;
            $oldPaidAmount  = $booking->paid_amount ?? 0;

            $oldAdvance = $oldPaidAmount > $oldTotalAmount ? ($oldPaidAmount - $oldTotalAmount) : 0;
            $oldDue     = $booking->due_amount ?? 0;

            // 2. CALCULATE NEW AMOUNTS
            $newTotalAmount = $totalAmountInclTax;
            $newPaidAmount  = $paidAmount;

            $newAdvance = $newPaidAmount > $newTotalAmount ? ($newPaidAmount - $newTotalAmount) : 0;
            $newDue     = $newTotalAmount > $newPaidAmount ? ($newTotalAmount - $newPaidAmount) : 0;

            // 3. FIND THE DIFFERENCE (This naturally revokes old amounts and applies new ones)
            $finalAdvanceAmount = $newAdvance - $oldAdvance;
            $finalDueAmount     = $newDue - $oldDue;

            // Determine credit/debit types for the ledger based on whether the amount went up or down
            $advanceType = $finalAdvanceAmount >= 0 ? 'credit' : 'debit';
            $dueType     = $finalDueAmount >= 0 ? 'credit' : 'debit';

            $this->clientBalanceService->manage([
                'clientId' => $clientId,
                'bookingId' => $booking->id,
                'dueAmount' => $finalDueAmount,
                'advanceAmount' => $finalAdvanceAmount,
                'dueType' => $dueType,
                'advanceType' => $advanceType,
                'callFrom' => 'booking',
                'actionFor' => 'adjustment',
                'description' => 'Adjustment by admin #' . $adminInfo?->id,
            ]);

            $beforeBookingStatus = $booking->status?->value;

            $booking->update(array_merge($validated, [
                'spend_amount' => $basePrice,
                'base_price' => $basePrice,
                'paid_amount' => $paidAmount,
                'due_amount' => $newDue,

                // Tax
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'total_amount_excl_tax' => $totalAmountExclTax,
                'total_amount_incl_tax' => $totalAmountInclTax,
            ]));

            $bookingClient = $booking->client;

            if (! empty($validated['status']) && $beforeBookingStatus != $validated['status'] && ! empty($bookingClient?->fcm_token)) {

                // Dispatch to a single token
                SendFirebaseNotificationJob::dispatch(
                    'token',
                    $bookingClient?->fcm_token,
                    $booking->status?->notificationTitle() . '#' . $booking->id,
                    $booking->status?->notificationDescription(),
                    [
                        'type' => 'booking_status',
                        'created_by' => Auth::guard('admin')->id(),
                        'remark' => "admin",
                        'additional' => notificationAdditionalArrayFormat([
                            'screen' => 'booking',
                            'booking_id' => $booking->id,
                            'client_id' => $booking->client_id
                        ])
                    ]
                );
            }

            DB::commit();
            return response()->json(['message' => 'Booking updated successfully.']);
        } catch (\Throwable $th) {
            DB::rollBack();

            dd($th->getMessage());

            return response()->json([
                'message' => 'Encounter error during update.',
            ], 422);
        }
    }

    public function massStatus(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
            'value' => 'required|string|in:pending,confirmed,cancelled,checked_in',
        ]);

        try {
            Booking::whereIn('id', $validated['indices'])->update(['status' => $validated['value']]);
            return response()->json(['message' => 'Bookings status updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during update.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();
            return response()->json(['message' => 'Booking deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during delete.'], 500);
        }
    }

    public function guests($id)
    {
        $guests = BookingGuest::where('booking_id', $id)->get();

        return response()->json([
            'guests' => $guests
        ]);
    }
    public function viewByClient(Request $request, $id)
    {
        $query = Booking::where('client_id', $id)->where('base_price', '>', 0)
            ->where('due_amount', '>', 0);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(15);

        return response()->json([
            'bookings' => $bookings->items(),
            'current_page' => $bookings->currentPage(),
            'last_page' => $bookings->lastPage(),
        ]);
    }

    public function massDestroy(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
        ]);

        try {
            Booking::whereIn('id', $validated['indices'])->delete();
            return response()->json(['message' => 'Bookings deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass delete.'], 500);
        }
    }
}
