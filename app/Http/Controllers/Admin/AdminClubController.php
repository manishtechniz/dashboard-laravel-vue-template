<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\BranchDataGrid;
use App\Http\Controllers\Admin\DataGrids\ClubDataGrid;
use App\Model\Branch;
use App\Model\Club;
use Illuminate\Http\Request;

class AdminClubController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            if (request()->has('list')) {
                return response()->json(Club::all());
            }
            if (request()->has('branches')) {
                return datagrid(BranchDataGrid::class)->process();
            }
            return datagrid(ClubDataGrid::class)->process();
        }

        $clubs = Club::all();
        return view('admin::clubs.index', compact('clubs'));
    }

    public function storeClub(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Club::create($validated);

        return response()->json([
            'message' => 'Club created successfully.',
            'clubs' => Club::all()
        ]);
    }

    public function updateClub(Request $request, $id)
    {
        $club = Club::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $club->update($validated);

        return response()->json([
            'message' => 'Club updated successfully.',
            'clubs' => Club::all()
        ]);
    }

    public function destroyClub($id)
    {
        $club = Club::findOrFail($id);
        $club->delete();

        return response()->json([
            'message' => 'Club deleted successfully.',
            'clubs' => Club::all()
        ]);
    }

    public function massDestroy(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
        ]);

        Club::whereIn('id', $validated['indices'])->delete();

        return response()->json(['message' => 'Clubs deleted successfully.']);
    }

    public function massUpdate(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
            'value' => 'required|boolean',
        ]);

        Club::whereIn('id', $validated['indices'])->update(['is_active' => $validated['value']]);

        return response()->json(['message' => 'Clubs status updated successfully.']);
    }

    // Branch Operations
    public function storeBranch(Request $request)
    {
        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Branch::create($validated);

        return response()->json(['message' => 'Branch created successfully.']);
    }

    public function updateBranch(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $branch->update($validated);

        return response()->json(['message' => 'Branch updated successfully.']);
    }

    public function destroyBranch($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return response()->json(['message' => 'Branch deleted successfully.']);
    }

    public function massDestroyBranch(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
        ]);

        Branch::whereIn('id', $validated['indices'])->delete();

        return response()->json(['message' => 'Branches deleted successfully.']);
    }

    public function massUpdateBranch(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
            'value' => 'required|boolean',
        ]);

        Branch::whereIn('id', $validated['indices'])->update(['is_active' => $validated['value']]);

        return response()->json(['message' => 'Branches status updated successfully.']);
    }
}
