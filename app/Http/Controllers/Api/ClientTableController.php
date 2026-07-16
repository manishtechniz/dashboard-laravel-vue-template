<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\ClubTable;
use App\Model\Floor;
use Illuminate\Http\Request;

class ClientTableController extends Controller
{
    public function index(Request $request)
    {
        $floors = Floor::with(['tables' => function ($query) {
            $query->where('status', 'available');
        }])
        ->where('is_active', true)
        ->get();

        return response()->json($floors);
    }

    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'capacity' => 'nullable|integer'
        ]);

        $tables = ClubTable::where('status', 'available');
        if ($validated['capacity']) {
            $tables->where('capacity', '>=', $validated['capacity']);
        }
        $tables = $tables->get();

        return response()->json($tables);
    }
}
