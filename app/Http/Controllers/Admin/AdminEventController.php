<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\EventDataGrid;
use App\Model\Club;
use App\Model\Event;
use Illuminate\Http\Request;

class AdminEventController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(EventDataGrid::class)->process();
        }

        $clubs = Club::all();
        return view('admin::events.index', compact('clubs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'cover_charge' => 'required|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
            'image' => 'nullable|string',
        ]);

        Event::create($validated);

        return response()->json(['message' => 'Event created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'cover_charge' => 'required|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
            'image' => 'nullable|string',
        ]);

        $event->update($validated);

        return response()->json(['message' => 'Event updated successfully.']);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Event deleted successfully.']);
    }
}
