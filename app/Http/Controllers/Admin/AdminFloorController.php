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
            'name' => 'required|string|max:255',
            'level' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        Floor::create($validated);

        return response()->json(['message' => 'Floor created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $floor = Floor::findOrFail($id);

        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'level' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $floor->update($validated);

        return response()->json(['message' => 'Floor updated successfully.']);
    }

    public function destroy($id)
    {
        $floor = Floor::findOrFail($id);
        $floor->delete();

        return response()->json(['message' => 'Floor deleted successfully.']);
    }

    public function massDestroy(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
        ]);

        Floor::whereIn('id', $validated['indices'])->delete();

        return response()->json(['message' => 'Floors deleted successfully.']);
    }

    public function massUpdate(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
            'value' => 'required|boolean',
        ]);

        Floor::whereIn('id', $validated['indices'])->update(['is_active' => $validated['value']]);

        return response()->json(['message' => 'Floors status updated successfully.']);
    }
}
