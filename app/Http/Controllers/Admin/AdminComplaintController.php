<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\ComplaintDataGrid;
use App\Model\Complaint;
use App\Model\Client;
use App\Model\Club;
use App\Model\Booking;
use Illuminate\Http\Request;

class AdminComplaintController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(ComplaintDataGrid::class)->process();
        }

        $clients = Client::select('id', 'name')->orderBy('name')->get();
        $clubs = Club::select('id', 'name')->orderBy('name')->get();

        return view('admin::complaints.index', compact('clients', 'clubs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'club_id' => 'nullable|integer|exists:clubs,id',
            'booking_id' => 'nullable|integer|exists:bookings,id',
            'message' => 'required|string|max:2000',
            'remark' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        try {
            Complaint::create($validated);
            return response()->json(['message' => 'Complaint created successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during create.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'club_id' => 'nullable|integer|exists:clubs,id',
            'booking_id' => 'nullable|integer|exists:bookings,id',
            'message' => 'required|string|max:2000',
            'remark' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        try {
            $complaint->update($validated);
            return response()->json(['message' => 'Complaint updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during update.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $complaint = Complaint::findOrFail($id);
            $complaint->delete();
            return response()->json(['message' => 'Complaint deleted successfully.']);
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
            Complaint::whereIn('id', $validated['indices'])->delete();
            return response()->json(['message' => 'Complaints deleted successfully.']);
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
            Complaint::whereIn('id', $validated['indices'])->update(['is_active' => $validated['value']]);
            return response()->json(['message' => 'Complaints status updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass update.'], 500);
        }
    }
}
