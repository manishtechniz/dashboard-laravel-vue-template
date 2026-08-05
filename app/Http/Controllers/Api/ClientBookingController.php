<?php

namespace App\Http\Controllers\Api;

use App\Enums\BookingStatus;
use App\Model\Booking;
use App\Model\BookingGuest;
use App\Model\ClientGuest;
use App\Model\ClubTable;
use App\Model\Event;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use OpenApi\Attributes as OA;

#[OA\Tag(name: "Bookings", description: "API Endpoints for Client Bookings")]
class ClientBookingController extends Controller
{
    public function __construct(
        private CouponService $couponService,
    ) {}

    #[OA\Get(
        path: "/api/bookings",
        summary: "List authenticated client bookings",
        tags: ["Bookings"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "club_id",
                in: "query",
                required: false,
                description: "Filter bookings by a specific club",
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "event_id",
                in: "query",
                required: false,
                description: "Filter bookings by a specific event",
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "table_id",
                in: "query",
                required: false,
                description: "Filter bookings by a specific table",
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "booking_date",
                in: "query",
                required: false,
                description: "Filter bookings by a specific date",
                schema: new OA\Schema(type: "date")
            )
        ],
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
        $validated = $request->validate([
            'club_id' => 'nullable|exists:clubs,id',
            'event_id' => 'nullable|exists:events,id',
        ]);

        $clubId = $validated['club_id'] ?? null;
        $eventId = $validated['event_id'] ?? null;
        $tableId = $validated['table_id'] ?? null;
        $bookingDate = $validated['booking_date'] ?? null;

        try {
            $bookings = $request->user()->bookings()
                ->when($clubId, function ($query) use ($clubId) {
                    $query->where('club_id', $clubId);
                })
                ->when($eventId, function ($query) use ($eventId) {
                    $query->where('event_id', $eventId);
                })
                ->when($tableId, function ($query) use ($tableId) {
                    $query->where('table_id', $tableId);
                })
                ->when($bookingDate, function ($query) use ($bookingDate) {
                    $query->whereDate('booking_date', $bookingDate);
                })
                ->with(['table:id,name', 'club:id,name', 'guests', 'event:id,name'])
                ->latest('id')
                ->paginate();

            return response()->json([
                'data' => $bookings
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Encounter error during booking request.',
                'error' => $th->getMessage(),
            ], 500);
        }
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
        try {
            $booking = $request->user()->bookings()
                ->where('id', $id)
                ->with(['table:id,name', 'club:id,name', 'guests'])
                ->first();

            if (!$booking) {
                return response()->json([
                    'message' => 'Record not found'
                ], 404);
            }

            return response()->json([
                'booking' => $booking
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Encounter error during booking request.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    #[OA\Post(
        path: "/api/bookings",
        summary: "Create a new booking request",
        tags: ["Bookings"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["booking_date", "table_id", "club_id", "guest_ids"],
                properties: [
                    new OA\Property(property: "table_id", type: "integer", example: 1),
                    new OA\Property(property: "event_id", type: "integer", example: 1),
                    new OA\Property(property: "club_id", type: "integer", example: 1),
                    new OA\Property(property: "booking_date", type: "string", format: "date", example: "2026-07-23"),
                    new OA\Property(property: "start_time", type: "string", example: "2:00"),
                    new OA\Property(property: "end_time", type: "string", example: "22:00"),
                    new OA\Property(property: "discount_source", type: "string", example: ""),
                    new OA\Property(property: "discount_code", type: "string", example: "ABCF123"),
                    new OA\Property(property: "discount_note", type: "string", example: ""),
                    new OA\Property(property: "special_requests", type: "string", example: "Say something related to booking or instruction something"),
                    new OA\Property(
                        property: "guest_ids",
                        type: "array",
                        items: new OA\Items(type: "integer", example: 5) // <-- Added this line
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

        $clubId = $request['club_id'] ?? null;
        $client = $request->user();
        $clientId = $client->id;

        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'table_id' => [
                'required',
                Rule::exists('tables', 'id')->where('club_id', $clubId),
            ],
            'event_id' => 'nullable|exists:events,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'discount_code' => 'nullable|max:100',
            'discount_source' => 'nullable|max:200',
            'discount_note' => 'nullable|max:500',
            'special_requests' => 'nullable|max:2000',
            'guest_ids' => 'nullable|array',
            'guest_ids.*' => [
                'nullable',
                'integer',
                Rule::exists('client_guests', 'id')->where(function ($query) use ($clientId) {
                    $query->where('client_id', $clientId);
                }),
            ],
        ]);

        DB::beginTransaction();

        try {
            $bookingDate = $validated['booking_date'];
            $tableId = $validated['table_id'];
            $eventId = $validated['event_id'] ?? null;
            $guestIds = $validated['guest_ids'] ?? [];

            unset($validated['guest_ids']);

            /**
             * Verify event.
             */
            $event = null;

            if (! empty($eventId)) {
                $event = Event::where('id', $eventId)
                    ->where('is_active', 1)
                    ->with('coupon:event_id,code')
                    ->first();

                // check if booking date associated with event date or not
                if (! $event && $event->event_date != $bookingDate) {
                    return response()->json(create422ErrorFormat('event_id', 'Oops! No events found on this date. Try picking another one!'), 422);
                }
            }

            /**
             * Check if table is available or not
             */
            $clubTable = ClubTable::where([
                'id' => $tableId,
                'status' => 'active',
                'club_id' => $clubId,
            ])
                ->with(['bookings' => function ($query) use ($bookingDate) {
                    $query->whereDate('booking_date', $bookingDate)
                        ->where('status', '!=', 'cancelled');
                }, 'club:id,name'])
                ->first();

            $totalBookings = $clubTable->bookings->count();
            $remainVipTable = max(0, $clubTable->total_tables - $totalBookings);
            $isAvailable = $remainVipTable > 0;

            if (!$isAvailable) {
                return response()->json(create422ErrorFormat('table_id', 'Selected table is not available'), 422);
            }

            // return [$clubTable];

            $basePrice = $clubTable->price ?? 0;
            $discount = 0;
            $discountType =  null;
            $maxDiscountAmount = 0;
            $couponCode = $validated['discount_code'] ?? null;

            // Coupon functionality
            if (! empty($couponCode)) {
                $couponService = $this->couponService->apply($couponCode, $basePrice, [
                    'event_id' => $eventId ?? null,
                    'booking_date' => $bookingDate ?? null,
                    'club_id' => $clubId ?? null,
                ]);

                if (! $couponService['status']) {
                    return response()->json($couponService, 422);
                }

                $discount = $couponService['discount'];
                $discountType = $couponService['discount_type'];
                $maxDiscountAmount = $couponService['max_discount_amount'];

                $couponService['instance']->increment('used_count');
                $couponService['instance']->save();
            }

            // Generate dynamic QR code payload
            $bookingCode = 'CLUB-' . strtoupper(uniqid()) . '-' . $client->id * 10;

            // Calculations
            $taxRate = 0;
            $totalAmountExclTax = $basePrice - $discount;
            $taxAmount = ($totalAmountExclTax * $taxRate) / 100;
            $totalAmountInclTax = $totalAmountExclTax + $taxAmount;


            $booking = $request->user()->bookings()->create(array_merge($validated, [
                'status' => 'pending',
                'payment_method' => 'cash',
                'payment_gateway' => 'cash',
                'payment_status' => 'pending',

                'qr_code' => $bookingCode,
                'guest_count' => count($guestIds),

                // Screenshot
                'club_name' => $clubTable->club->name,
                'client_name' => $client->name ?? null,
                'client_phone' => $client->phone ?? null,
                'client_email' => $client->email ?? null,

                'spend_amount' => $basePrice,
                'base_price' => $basePrice,

                // Discount
                'discount_type' => $discountType,
                'discount_amount' => $discount,
                'max_discount_amount' => $maxDiscountAmount,
                // discount_code, discount_source, discount_note filled by request

                // Tax
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'total_amount_excl_tax' => $totalAmountExclTax,
                'total_amount_incl_tax' => $totalAmountInclTax,
            ]));

            $clientGuests = ClientGuest::whereIn('id', $guestIds)->get();

            $prepareBookingGuest = [];

            foreach ($clientGuests as $clientGuest) {
                $prepareBookingGuest[] = [
                    'booking_id' => $booking->id,
                    'client_id' => $clientId,
                    'guest_id' => $clientGuest->id,
                    'email' => $clientGuest->email ?? null,
                    'phone' => $clientGuest->phone ?? null,
                    'name' => $clientGuest->name ?? null,
                    'age' => $clientGuest->age ?? null,
                    'gender' => $clientGuest->gender ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            BookingGuest::insert($prepareBookingGuest);

            DB::commit();

            return response()->json([
                'message' => 'Booking request submitted successfully.',
                'booking' => $booking,
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Encounter error during booking request.',
                'error' => $th->getMessage(),
            ], 500);
        }
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
        $booking = $request->user()->bookings()->where('id', $id)->first();

        if (!$booking) {
            return response()->json([
                'message' => 'Record not found.'
            ], 404);
        }

        if ($booking->status === BookingStatus::CHECKED_IN) {
            return response()->json(['message' => 'Checked in bookings cannot be cancelled.'], 422);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Booking cancelled successfully.']);
    }
}
