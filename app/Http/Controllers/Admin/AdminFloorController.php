<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\FloorDataGrid;
use App\Model\Branch;
use App\Model\Floor;
use Illuminate\Http\Request;

class AdminFloorController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(FloorDataGrid::class)->process();
        }

        $branches = Branch::all();
        return view('admin::floors.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:256',
            'level' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        try {
            Floor::create($validated);
            return response()->json(['message' => 'Floor created successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during create.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $floor = Floor::findOrFail($id);

        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:2000',
            'level' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        try {
            $floor->update($validated);
            return response()->json(['message' => 'Floor updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during update.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $floor = Floor::findOrFail($id);
            $floor->delete();
            return response()->json(['message' => 'Floor deleted successfully.']);
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
            Floor::whereIn('id', $validated['indices'])->delete();
            return response()->json(['message' => 'Floors deleted successfully.']);
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
            Floor::whereIn('id', $validated['indices'])->update(['is_active' => $validated['value']]);
            return response()->json(['message' => 'Floors status updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass update.'], 500);
        }
    }
}
