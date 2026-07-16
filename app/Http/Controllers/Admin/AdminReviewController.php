<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\ReviewDataGrid;
use App\Model\Review;

class AdminReviewController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(ReviewDataGrid::class)->process();
        }

        return view('admin::reviews.index');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully.']);
    }
}
