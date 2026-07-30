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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:pending,reviewing,planned,in_progress,completed,rejected',
            'priority' => 'required|string|in:low,medium,high',
        ]);

        FeatureRequest::create($validated);

        return response()->json(['message' => 'Feature request created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $featureRequest = FeatureRequest::findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'nullable|integer|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:pending,reviewing,planned,in_progress,completed,rejected',
            'priority' => 'required|string|in:low,medium,high',
        ]);

        $featureRequest->update($validated);

        return response()->json(['message' => 'Feature request updated successfully.']);
    }

    public function destroy($id)
    {
        $featureRequest = FeatureRequest::findOrFail($id);
        $featureRequest->delete();

        return response()->json(['message' => 'Feature request deleted successfully.']);
    }
}
