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

        Review::create($validated);

        return response()->json(['message' => 'Review created successfully.']);
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

        $review->update($validated);

        return response()->json(['message' => 'Review updated successfully.']);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully.']);
    }

    public function massDestroy(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
        ]);

        Review::whereIn('id', $validated['indices'])->delete();

        return response()->json(['message' => 'Reviews deleted successfully.']);
    }

    public function massUpdate(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
            'value' => 'required|boolean',
        ]);

        Review::whereIn('id', $validated['indices'])->update(['is_active' => $validated['value']]);

        return response()->json(['message' => 'Reviews status updated successfully.']);
    }
}
