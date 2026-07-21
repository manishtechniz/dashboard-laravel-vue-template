<?php

namespace App\Http\Controllers\Api;

use App\Model\Booking;
use App\Model\Payment;
use App\Model\Transaction;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Payments", description: "API Endpoints for Client Payments")]
class ClientPaymentController extends Controller
{
    #[OA\Post(
        path: "/api/payments/pay",
        summary: "Process payment for a booking",
        tags: ["Payments"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["booking_id", "amount", "payment_method", "token"],
                properties: [
                    new OA\Property(property: "booking_id", type: "integer", example: 1),
                    new OA\Property(property: "amount", type: "number", format: "float", example: 100.50),
                    new OA\Property(property: "payment_method", type: "string", example: "card"),
                    new OA\Property(property: "token", type: "string", example: "tok_visa")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Payment processed and booking confirmed",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Payment processed and booking confirmed."),
                        new OA\Property(property: "payment", type: "object"),
                        new OA\Property(property: "transaction", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation errors"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function pay(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'token' => 'required|string', // stripe token simulation
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);

        // Create Payment record
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'status' => 'completed',
            'transaction_reference' => 'ch_' . uniqid()
        ]);

        // Create Transaction log
        $transaction = Transaction::create([
            'payment_id' => $payment->id,
            'booking_id' => $booking->id,
            'amount' => $payment->amount,
            'type' => 'charge',
            'status' => 'success',
            'reference' => $payment->transaction_reference,
            'response_payload' => [
                'gateway' => 'stripe_simulated',
                'status' => 'paid',
                'fee_collected' => 0.50
            ]
        ]);

        // Update booking status
        $booking->update(['status' => 'confirmed']);

        return response()->json([
            'message' => 'Payment processed and booking confirmed.',
            'payment' => $payment,
            'transaction' => $transaction
        ]);
    }
}
