<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\EventDataGrid;
use App\Model\Club;
use App\Model\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminEventController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(EventDataGrid::class)->process();
        }

        $clubs = Club::get(['id', 'name']);

        // dd($clubs);
        return view('admin::events.index', compact('clubs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $event = Event::create($validated);
        // dd($validated);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events');
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('events');
        }

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
            'event_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image && Storage::exists($event->image)) {
                Storage::delete($event->image);
            }

            $validated['image'] = $request->file('image')->store('events');
        }

        if ($request->hasFile('featured_image')) {
            if ($event->featured_image && Storage::exists($event->featured_image)) {
                Storage::delete($event->featured_image);
            }

            $validated['featured_image'] = $request->file('featured_image')->store('events');
        }

        $event->update($validated);

        return response()->json(['message' => 'Event updated successfully.']);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->image && Storage::exists($event->image)) {
            Storage::delete($event->image);
        }

        if ($event->featured_image && Storage::exists($event->featured_image)) {
            Storage::delete($event->featured_image);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully.']);
    }

    public function massDestroy(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
        ]);

        $events = Event::whereIn('id', $validated['indices'])->get();

        foreach ($events as $event) {
            if ($event->image && Storage::exists($event->image)) {
                Storage::delete($event->image);
            }
            if ($event->featured_image && Storage::exists($event->featured_image)) {
                Storage::delete($event->featured_image);
            }
            $event->delete();
        }

        return response()->json(['message' => 'Events deleted successfully.']);
    }
}
