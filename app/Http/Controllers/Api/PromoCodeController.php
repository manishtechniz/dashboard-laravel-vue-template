<?php

namespace App\Http\Controllers\Api;

use App\Model\ClubTable;
use App\Model\Floor;
use App\Model\PromoCode;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Promo Code", description: "API Endpoints for Promo Code")]
class PromoCodeController extends Controller
{
    #[OA\Get(
        path: "/api/promo-codes",
        summary: "List active promo code",
        tags: ["Promo Code"],
        security: [["bearerAuth" => []]],
        parameters: [],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of active code",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(Request $request)
    {
        $validated = $request->validate([]);

        try {
            $codes = PromoCode::where('is_active', 1)->paginate();

            return response()->json([
                "data" => $codes
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Encountered error during fetch promo codes.'
            ], 500);
        }
    }
}
