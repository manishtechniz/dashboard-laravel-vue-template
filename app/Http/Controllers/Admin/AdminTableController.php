<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\TableDataGrid;
use App\Model\Club;
use App\Model\ClubTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminTableController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(TableDataGrid::class)->process();
        }

        $clubs = Club::all();
        return view('admin::tables.index', compact('clubs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'name' => 'required|string|max:256',
            'label' => 'required|string|max:256',
            'disclaimer' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0',
            'cover_charge' => 'required|numeric|min:0',
            'late_cover_charge' => 'required|numeric|gte:cover_charge',
            'capacity' => 'required|integer|min:1',
            'total_tables' => 'required|integer|min:0',
            'status' => 'required|string|in:active,inactive',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp',
        ]);

        try {
            $validated['image'] = $request->file('image')->store('tables');

            ClubTable::create($validated);

            return response()->json(['message' => 'Table created successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during create.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        // dd(1);
        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'name' => 'required|string|max:2000',
            'disclaimer' => 'nullable|string|max:2000',
            'label' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0',
            'cover_charge' => 'required|numeric|min:0',
            'late_cover_charge' => 'required|numeric|gte:cover_charge',
            'capacity' => 'required|integer|min:1',
            'total_tables' => 'required|integer|min:0',
            'status' => 'required|string|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $table = ClubTable::findOrFail($id);

            unset($validated['image']);

            // 3. Handle image Upload
            if ($request->hasFile('image')) {
                if ($table->image && Storage::exists($table->image)) {
                    Storage::delete($table->image);
                }

                // Store new image and update the data array with the path
                $validated['image'] = $request->file('image')->store('tables');
            }

            $table->update($validated);

            return response()->json(['message' => 'Table updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during update.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $table = ClubTable::findOrFail($id);
            if ($table->image && Storage::exists($table->image)) {
                Storage::delete($table->image);
            }
            $table->delete();
            return response()->json(['message' => 'Table deleted successfully.']);
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
            $tables = ClubTable::whereIn('id', $validated['indices'])->get();
            foreach ($tables as $table) {
                if ($table->image && Storage::exists($table->image)) {
                    Storage::delete($table->image);
                }
                $table->delete();
            }
            return response()->json(['message' => 'Tables deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass delete.'], 500);
        }
    }

    public function massUpdate(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
            'value' => 'required|string|in:active,inactive',
        ]);

        try {
            ClubTable::whereIn('id', $validated['indices'])->update(['status' => $validated['value']]);
            return response()->json(['message' => 'Tables status updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass update.'], 500);
        }
    }
}
