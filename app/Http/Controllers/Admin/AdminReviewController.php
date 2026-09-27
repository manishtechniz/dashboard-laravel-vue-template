<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\ReviewDataGrid;
use App\Model\Review;
use App\Model\Client;
use App\Model\Club;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(ReviewDataGrid::class)->process();
        }

        $clients = Client::select('id', 'name')->orderBy('name')->get();
        $clubs = Club::select('id', 'name')->orderBy('name')->get();

        return view('admin::reviews.index', compact('clients', 'clubs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'club_id' => 'nullable|integer|exists:clubs,id',
            'booking_id' => 'nullable|integer|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'is_active' => 'boolean',
            'is_anonymous' => 'boolean',
            'comment' => 'nullable|string|max:2000',
            'remark' => 'nullable|string|max:1000',
        ]);

        try {
            Review::create($validated);
            return response()->json(['message' => 'Review created successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during create.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'club_id' => 'nullable|integer|exists:clubs,id',
            'booking_id' => 'nullable|integer|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'is_active' => 'boolean',
            'is_anonymous' => 'boolean',
            'comment' => 'nullable|string|max:2000',
            'remark' => 'nullable|string|max:1000',
        ]);

        try {
            $review->update($validated);
            return response()->json(['message' => 'Review updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during update.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Review::destroy($id);

            return response()->json(['message' => 'Review deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during delete.'], 500);
        }
    }

    public function massDestroy(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
        ]);

        try {
            foreach ($validated['indices'] as $id) {
                Review::destroy($id);
            }

            return response()->json(['message' => 'Reviews deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass delete.'], 500);
        }
    }

    public function massUpdate(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
            'value' => 'required|boolean',
        ]);

        try {
            Review::whereIn('id', $validated['indices'])->update(['is_active' => $validated['value']]);
            return response()->json(['message' => 'Reviews status updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass update.'], 500);
        }
    }
}
