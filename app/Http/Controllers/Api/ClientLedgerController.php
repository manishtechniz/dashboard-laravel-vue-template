<?php

namespace App\Http\Controllers\Api;

use App\Model\ClientLedger;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Client Ledgers", description: "API Endpoints for Client Ledgers")]
class ClientLedgerController extends Controller
{
    #[OA\Get(
        path: "/api/ledgers",
        summary: "Get client ledgers with pagination",
        tags: ["Client Ledgers"],
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
                description: "Client ledgers retrieved successfully",
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

            $ledgers = $request->user()->client_ledgers()
                ->orderBy('id', 'desc')
                ->paginate(min(100, $perPage));

            return response()->json([
                'success' => true,
                'data' => $ledgers,
                'message' => 'Client ledgers retrieved successfully.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Oops! Encountered an error during processing the request.'
            ], 500);
        }
    }
}
