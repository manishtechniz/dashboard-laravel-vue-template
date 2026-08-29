<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\FeatureRequestDataGrid;
use App\Model\FeatureRequest;
use App\Model\Client;
use Illuminate\Http\Request;

class AdminFeatureRequestController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(FeatureRequestDataGrid::class)->process();
        }

        $clients = Client::select('id', 'name')->orderBy('name')->get();

        return view('admin::feature_requests.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|integer|exists:clients,id',
            'title' => 'required|string|max:256',
            'description' => 'required|string',
            'status' => 'required|string|in:pending,reviewing,planned,in_progress,completed,rejected',
            'priority' => 'required|string|in:low,medium,high',
        ]);

        try {
            FeatureRequest::create($validated);
            return response()->json(['message' => 'Feature request created successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during create.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $featureRequest = FeatureRequest::findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'nullable|integer|exists:clients,id',
            'title' => 'required|string|max:2000',
            'description' => 'required|string',
            'status' => 'required|string|in:pending,reviewing,planned,in_progress,completed,rejected',
            'priority' => 'required|string|in:low,medium,high',
        ]);

        try {
            $featureRequest->update($validated);
            return response()->json(['message' => 'Feature request updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during update.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $featureRequest = FeatureRequest::findOrFail($id);
            $featureRequest->delete();
            return response()->json(['message' => 'Feature request deleted successfully.']);
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
            FeatureRequest::whereIn('id', $validated['indices'])->delete();
            return response()->json(['message' => 'Feature requests deleted successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass delete.'], 500);
        }
    }

    public function massUpdate(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
            'value' => 'required|string|in:pending,reviewing,planned,in_progress,completed,rejected',
        ]);

        try {
            FeatureRequest::whereIn('id', $validated['indices'])->update(['status' => $validated['value']]);
            return response()->json(['message' => 'Feature requests status updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass update.'], 500);
        }
    }
}
