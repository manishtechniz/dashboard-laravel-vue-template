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
            'name' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'cover_charge' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'total_tables' => 'required|integer|min:0',
            'status' => 'required|string|in:active,inactive',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp',
        ]);


        // $fileData = $request->file('image')->store('tables');

        // dd($fileData);

        $validated['image'] = $request->file('image')->store('tables');

        ClubTable::create($validated);

        return response()->json(['message' => 'Table created successfully.']);
    }

    public function update(Request $request, $id)
    {
        // dd(1);
        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'name' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'cover_charge' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'total_tables' => 'required|integer|min:0',
            'status' => 'required|string|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $table = ClubTable::findOrFail($id);

        unset($validated['image']);

        // 3. Handle Avatar Upload
        if ($request->hasFile('image')) {
            if ($table->avatar && Storage::exists($table->avatar)) {
                Storage::delete($table->avatar);
            }

            // Store new avatar and update the data array with the path
            $validated['image'] = $request->file('image')->store('tables');
        }

        $table->update($validated);

        return response()->json(['message' => 'Table updated successfully.']);
    }

    public function destroy($id)
    {
        $table = ClubTable::findOrFail($id);
        $table->delete();

        return response()->json(['message' => 'Table deleted successfully.']);
    }

    public function massDestroy(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
        ]);

        ClubTable::whereIn('id', $validated['indices'])->delete();

        return response()->json(['message' => 'Tables deleted successfully.']);
    }

    public function massUpdate(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
            'value' => 'required|string|in:active,inactive',
        ]);

        ClubTable::whereIn('id', $validated['indices'])->update(['status' => $validated['value']]);

        return response()->json(['message' => 'Tables status updated successfully.']);
    }
}
