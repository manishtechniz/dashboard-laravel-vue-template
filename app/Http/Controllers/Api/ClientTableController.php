<?php

namespace App\Http\Controllers\Api;

use App\Model\ClubTable;
use App\Model\Floor;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Tables", description: "API Endpoints for Table")]
class ClientTableController extends Controller
{
    #[OA\Get(
        path: "/api/tables",
        summary: "List active tables",
        tags: ["Tables"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "booking_date",
                in: "query",
                required: true,
                schema: new OA\Schema(type: "string", format: "date", example: "2026-07-26")
            ),
            new OA\Parameter(
                name: "club_id",
                in: "query",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of active tables",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            ),
            new OA\Response(response: 401, description: "Unauthenticated")
        ]
    )]
    public function index(Request $request)
    {
        $validated = $request->validate([
            'booking_date' => 'required|date_format:Y-m-d',
            'club_id'      => 'required',
        ]);

        $personalizedEvents = [
            ['key' => 'breakup_divorce_party', 'label' => 'Breakup / Divorce Party'],
            ['key' => 'personal_birthday', 'label' => 'My Birthday'],
            ['key' => 'wife_birthday', 'label' => 'Wife\'s Birthday'],
            ['key' => 'husband_birthday', 'label' => 'Husband\'s Birthday'],
            ['key' => 'partner_birthday', 'label' => 'Partner / Significant Other\'s Birthday'],
            ['key' => 'friend_birthday', 'label' => 'Friend\'s Birthday'],
            ['key' => 'milestone_birthday', 'label' => 'Milestone Birthday (18th, 21st, 30th, etc.)'],
            ['key' => 'anniversary', 'label' => 'Anniversary'],
            ['key' => 'bachelor_party', 'label' => 'Bachelor Party / Stag Do'],
            ['key' => 'bachelorette_party', 'label' => 'Bachelorette Party / Hen Do'],
            ['key' => 'engagement_party', 'label' => 'Engagement Celebration'],
            ['key' => 'date_night', 'label' => 'Special Date Night'],
            ['key' => 'graduation_party', 'label' => 'Graduation Celebration'],
            ['key' => 'job_promotion', 'label' => 'Job Promotion'],
            ['key' => 'farewell_party', 'label' => 'Farewell / Going Away Party'],
            ['key' => 'corporate_event', 'label' => 'Office / Corporate Party'],
            ['key' => 'exam_finish', 'label' => 'End of Exams / Semester'],
            ['key' => 'girls_night_out', 'label' => 'Girls\' Night Out'],
            ['key' => 'boys_night_out', 'label' => 'Boys\' Night Out'],
            ['key' => 'reunion', 'label' => 'Reunion (School / College / Friends)'],
            ['key' => 'welcome_back', 'label' => 'Welcome Back / Return Party'],
            ['key' => 'vip_hosting', 'label' => 'Hosting VIPs / Clients'],
            ['key' => 'just_because', 'label' => 'Just Because / Weekend Vibes'],
        ];

        try {
            //code... 
            $bookingDate = $validated['booking_date'] ?? now()->toDateString();
            $clubId = $validated['club_id'];

            $tables = ClubTable::where([
                'status' => 'active',
                'club_id' => $clubId,
            ])
                ->with(['bookings' => function ($query) use ($bookingDate) {
                    $query->whereDate('booking_date', $bookingDate)
                        ->where('status', '!=', 'cancelled');
                }])
                ->get();

            $tablesData = $tables->map(function ($table) {
                $totalBookings = $table->bookings->count();
                $remainVipTable = max(0, $table->total_tables - $totalBookings);
                $isAvailable = $remainVipTable > 0;

                $tableArray = $table->toArray();
                $tableArray['remain_table'] = $remainVipTable;
                $tableArray['is_avaliable'] = $isAvailable;

                unset($tableArray['bookings']);

                return $tableArray;
            });

            return response()->json([
                'data' => $tablesData,
                'personalized_events' => $personalizedEvents,
            ]);
        } catch (\Throwable $th) {
            echo $th->getMessage();
            return response()->json([
                'message' => 'Encountered error during fetch tables.'
            ], 500);
        }
    }
}
