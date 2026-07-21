<?php

namespace App\Http\Controllers\Api;

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
        $notifications = $request->user()->notifications()->latest()->get();

        return response()->json($notifications);
    }

    #[OA\Post(
        path: "/api/notifications/{id}/read",
        summary: "Mark notification as read",
        tags: ["Notifications"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "Notification ID", schema: new OA\Schema(type: "integer"))
        ],
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
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->update(['read_at' => now()]);

        return response()->json(['message' => 'Notification marked as read.']);
    }

    #[OA\Post(
        path: "/api/notifications/tokens",
        summary: "Register device push notification token",
        tags: ["Notifications"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["token", "device_type"],
                properties: [
                    new OA\Property(property: "token", type: "string", example: "fcm_device_token_xyz"),
                    new OA\Property(property: "device_type", type: "string", enum: ["ios", "android", "web"], example: "android")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Device token registered successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Device token registered successfully.")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation errors"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function storeToken(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'device_type' => 'required|string|in:ios,android,web',
        ]);

        DeviceToken::updateOrCreate(
            ['client_id' => $request->user()->id, 'token' => $validated['token']],
            ['device_type' => $validated['device_type']]
        );

        return response()->json(['message' => 'Device token registered successfully.']);
    }
}
