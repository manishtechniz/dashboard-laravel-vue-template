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

        Complaint::create($validated);

        return response()->json(['message' => 'Complaint created successfully.']);
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

        $complaint->update($validated);

        return response()->json(['message' => 'Complaint updated successfully.']);
    }

    public function destroy($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();

        return response()->json(['message' => 'Complaint deleted successfully.']);
    }
}
