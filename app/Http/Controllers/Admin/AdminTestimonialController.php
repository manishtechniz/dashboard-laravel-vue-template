<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\TestimonialDataGrid;
use App\Model\Testimonial;
use App\Model\Client;
use App\Model\Club;
use Illuminate\Http\Request;

class AdminTestimonialController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(TestimonialDataGrid::class)->process();
        }

        $clients = Client::select('id', 'name')->orderBy('name')->get();
        $clubs = Club::select('id', 'name')->orderBy('name')->get();

        return view('admin::testimonials.index', compact('clients', 'clubs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'club_id' => 'nullable|integer|exists:clubs,id',
            'rating' => 'required|integer|min:1|max:5',
            'is_published' => 'boolean',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|max:2000',
        ]);

        try {
            Testimonial::create($validated);
            return response()->json(['message' => 'Testimonial created successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during create.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'club_id' => 'nullable|integer|exists:clubs,id',
            'rating' => 'required|integer|min:1|max:5',
            'is_published' => 'boolean',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|max:2000',
        ]);

        try {
            $testimonial->update($validated);
            return response()->json(['message' => 'Testimonial updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during update.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Testimonial::destroy($id);
            return response()->json(['message' => 'Testimonial deleted successfully.']);
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
            foreach ($validated['indices'] as $id) {
                Testimonial::destroy($id);
            }
            return response()->json(['message' => 'Testimonials deleted successfully.']);
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
            Testimonial::whereIn('id', $validated['indices'])->update(['is_published' => $validated['value']]);
            return response()->json(['message' => 'Testimonials status updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass update.'], 500);
        }
    }
}
