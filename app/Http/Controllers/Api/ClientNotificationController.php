<?php

namespace App\Http\Controllers\Api;

use App\Jobs\SendFirebaseNotificationJob;
use App\Model\DeviceToken;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Notifications", description: "API Endpoints for Client Notifications & Push Tokens")]
class ClientNotificationController extends Controller
{
    #[OA\Get(
        path: "/api/notifications",
        summary: "List authenticated client notifications",
        tags: ["Notifications"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of notifications",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()
            ->paginate();

        return response()->json([
            'data' => $notifications
        ]);
    }

    #[OA\Post(
        path: "/api/notifications/read",
        summary: "Mark multiple notifications as read",
        tags: ["Notifications"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: "Payload containing an array of notification IDs",
            content: new OA\JsonContent(
                required: ["ids"],
                properties: [
                    new OA\Property(
                        property: "ids",
                        type: "array",
                        description: "Array of notification IDs (UUIDs by default in Laravel)",
                        items: new OA\Items(type: "string", example: "9a3b5c7d-1234-5678-90ab-cdef12345678")
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Notification marked as read",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Notification marked as read.")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 404, description: "Notification not found")
        ]
    )]
    public function markAsRead(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
        ]);

        $request->user()->notifications()
            ->whereIn('id', $validated['ids'])
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Notification marked as read.']);
    }

    #[OA\Post(
        path: "/api/notifications/test-send-user",
        summary: "Test sending notification to a single user",
        tags: ["Notifications"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["fcm_token"],
                properties: [
                    new OA\Property(property: "fcm_token", type: "string", example: "fcm_device_token_xyz")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Notification queued successfully!",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Notification queued successfully!")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation errors")
        ]
    )]
    public function testSendToUser(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        // Dispatch to a single token
        SendFirebaseNotificationJob::dispatch(
            'token',
            $request->fcm_token,
            'Order Shipped!',
            'Your order is on the way.',
            ['order_id' => '12345']
        );

        return response()->json(['message' => 'Notification queued successfully!']);
    }

    #[OA\Post(
        path: "/api/notifications/test-send-multiple",
        summary: "Test sending notification to multiple users",
        tags: ["Notifications"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["fcm_tokens"],
                properties: [
                    new OA\Property(
                        property: "fcm_tokens",
                        type: "array",
                        items: new OA\Items(type: "string", example: "fcm_device_token_xyz")
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Mass notifications queued!",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Mass notifications queued!")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation errors")
        ]
    )]
    public function testSendToMultiple(Request $request)
    {
        $validated = $request->validate([
            'fcm_tokens' => 'required|array',
        ]);

        // Dispatch to an array of tokens
        SendFirebaseNotificationJob::dispatch(
            'tokens',
            $validated['fcm_tokens'],
            'Flash Sale!',
            '50% off everything today only.',
            ['sale_id' => 'FLASH50']
        );

        return response()->json(['message' => 'Mass notifications queued!']);
    }
}
