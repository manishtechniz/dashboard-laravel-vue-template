<?php

namespace App\Http\Controllers\Api;

use App\Model\ClientGuest;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Client Guests", description: "API Endpoints for Client Guest List Management")]
class ClientGuestController extends Controller
{
    #[OA\Get(
        path: "/api/guests",
        summary: "List all guests of the client",
        tags: ["Client Guests"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of guests",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(Request $request)
    {
        $guests = $request->user()->guests()
            ->latest()
            ->get();

        return response()->json([
            'data' => $guests
        ]);
    }

    #[OA\Post(
        path: "/api/guests",
        summary: "Add one or multiple guests to the client guest list",
        tags: ["Client Guests"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: "guests",
                        type: "array",
                        items: new OA\Items(
                            required: ["name", "age", "gender"],
                            properties: [
                                new OA\Property(property: "name", type: "string", example: "Jane Doe"),
                                new OA\Property(property: "email", type: "string", format: "email", example: "jane@example.com"),
                                new OA\Property(property: "phone", type: "string", example: "+1234567890"),
                                new OA\Property(property: "age", type: "string", example: "24"),
                                new OA\Property(property: "gender", type: "string", example: "female")
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Guests added successfully",
                content: new OA\JsonContent(type: "object")
            ),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guests' => 'required|array',
            'guests.*.name' => 'required|string|max:255',
            'guests.*.email' => 'nullable|email|max:255',
            'guests.*.phone' => 'nullable|string|max:255',
            'guests.*.age' => 'nullable|string|max:255',
            'guests.*.gender' => 'nullable|string|max:255',
        ]);

        $createdGuests = [];
        foreach ($validated['guests'] as $guestData) {
            $createdGuests[] = $request->user()->guests()->create($guestData);
        }

        return response()->json([
            'message' => 'Guests added successfully.',
            'guests' => $createdGuests
        ], 201);
    }

    #[OA\Put(
        path: "/api/guests",
        summary: "Sync / Bulk update client guest list",
        tags: ["Client Guests"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["guests"],
                properties: [
                    new OA\Property(
                        property: "guests",
                        type: "array",
                        items: new OA\Items(
                            required: ["name", "age", "gender"],
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 3),
                                new OA\Property(property: "name", type: "string", example: "John Doe Updated"),
                                new OA\Property(property: "email", type: "string", format: "email", example: "john_updated@example.com"),
                                new OA\Property(property: "phone", type: "string", example: "+1234567890"),
                                new OA\Property(property: "age", type: "string", example: "26"),
                                new OA\Property(property: "gender", type: "string", example: "male")
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Guest list updated and synchronized successfully",
                content: new OA\JsonContent(type: "object")
            ),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function update(Request $request)
    {
        // return $request->all();
        // return 1;
        $validated = $request->validate([
            'guests' => 'required|array',
            'guests.*.id' => 'nullable|integer|exists:client_guests,id',
            'guests.*.name' => 'required|string|max:255',
            'guests.*.email' => 'nullable|email|max:255',
            'guests.*.phone' => 'nullable|string|max:255',
            'guests.*.age' => 'nullable|string|max:255',
            'guests.*.gender' => 'nullable|string|max:255',
        ]);

        $client = $request->user();
        $inputGuests = $validated['guests'];

        // Get all existing guests for this client
        $existingGuestIds = $client->guests()->pluck('id')->toArray();

        $processedIds = [];

        foreach ($inputGuests as $guestData) {
            if (!empty($guestData['id'])) {
                // Ensure the guest belongs to this client before updating
                if (in_array($guestData['id'], $existingGuestIds)) {
                    $guest = ClientGuest::find($guestData['id']);
                    $guest->update($guestData);
                    $processedIds[] = $guest->id;
                }
            } else {
                // Create a new guest
                $guest = $client->guests()->create($guestData);
                $processedIds[] = $guest->id;
            }
        }

        // Delete any guest that was NOT in the processed list
        $idsToDelete = array_diff($existingGuestIds, $processedIds);
        if (!empty($idsToDelete)) {
            ClientGuest::destroy($idsToDelete);
        }

        return response()->json([
            'message' => 'Guest list synchronized successfully.',
            'guests' => $client->guests()->latest()->get()
        ]);
    }

    #[OA\Delete(
        path: "/api/guests/{id}",
        summary: "Remove guest from list",
        tags: ["Client Guests"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "Guest ID", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Guest removed successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Guest removed successfully.")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Guest not found"),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function destroy(Request $request, $id)
    {
        $guest = $request->user()->guests()->findOrFail($id);
        $guest->delete();

        return response()->json([
            'message' => 'Guest removed successfully.'
        ]);
    }
}
