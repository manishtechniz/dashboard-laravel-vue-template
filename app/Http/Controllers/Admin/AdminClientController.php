<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\ClientDataGrid;
use App\Model\Client;
use Illuminate\Http\Request;

class AdminClientController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(ClientDataGrid::class)->process();
        }

        return view('admin::clients.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients,email',
            'phone' => 'nullable|string|unique:clients,phone',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        Client::create($validated);

        return response()->json([
            'message' => 'Client created successfully.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|unique:clients,phone,' . $client->id,
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $client->update($validated);

        return response()->json([
            'message' => 'Client updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id); 

        $client->delete();

        return response()->json([
            'message' => 'Client deleted successfully.',
        ]);
    }

    public function massDestroy(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
        ]);

        Client::whereIn('id', $validated['indices'])->delete();

        return response()->json(['message' => 'Clients deleted successfully.']);
    }

    public function massUpdate(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
            'value' => 'required|boolean',
        ]);

        Client::whereIn('id', $validated['indices'])->update(['is_active' => $validated['value']]);

        return response()->json(['message' => 'Clients status updated successfully.']);
    }
}
