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
    #[OA\Get(
        path: "/api/payments",
        summary: "Get client payments with pagination",
        tags: ["Payments"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "per_page",
                in: "query",
                required: false,
                description: "Number of items per page",
                schema: new OA\Schema(type: "integer", default: 10)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Client payments retrieved successfully",
                content: new OA\JsonContent(type: "object")
            ),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 500, description: "Server Error")
        ]
    )]
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);

            $paymentType = [
                'withdrawal_advance' => 'Withdrawal',
                'advance' => 'Deposit',
                'due_clearance' => 'Due Clearance',
            ];

            $payments = $request->user()->client_payments()
                ->orderBy('id', 'desc')
                ->paginate(min(100, $perPage));

            return response()->json([
                'success' => true,
                'paymentType' => $paymentType,
                'data' => $payments,
                'message' => 'Client payments retrieved successfully.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Encountered an error during processing the request.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

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
        return [];
    }
}
