<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\TableDataGrid;
use App\Model\ClubTable;
use App\Model\Floor;
use Illuminate\Http\Request;

class AdminTableController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(TableDataGrid::class)->process();
        }

        $floors = Floor::all();
        return view('admin::tables.index', compact('floors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|string|in:available,reserved,occupied,maintenance',
            'x_position' => 'nullable|integer',
            'y_position' => 'nullable|integer',
        ]);

        ClubTable::create($validated);

        return response()->json(['message' => 'Table created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $table = ClubTable::findOrFail($id);

        $validated = $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|string|in:available,reserved,occupied,maintenance',
            'x_position' => 'nullable|integer',
            'y_position' => 'nullable|integer',
        ]);

        $table->update($validated);

        return response()->json(['message' => 'Table updated successfully.']);
    }

    public function destroy($id)
    {
        $table = ClubTable::findOrFail($id);
        $table->delete();

        return response()->json(['message' => 'Table deleted successfully.']);
    }
}
