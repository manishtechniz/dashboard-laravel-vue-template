<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\ClientDataGrid;
use App\Model\Client;
use App\Model\ClientLedger;
use App\Model\MobileAppRole;
use Illuminate\Http\Request;

class AdminClientController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(ClientDataGrid::class)->process();
        }

        $roles = MobileAppRole::all();

        return view('admin::clients.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:256',
            'email' => 'nullable|email|unique:clients,email',
            'phone' => 'nullable|string|unique:clients,phone',
            'password' => 'required|max:100|min:5',
            'role_id' => 'nullable|exists:mobile_app_roles,id',
            'is_active' => 'boolean',
        ]);

        try {
            if ($request->filled('password')) {
                $validated['password'] = bcrypt($request->password);
            }

            Client::create($validated);

            return response()->json([
                'message' => 'Client created successfully.',
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during create.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:2000',
            'email' => 'nullable|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|unique:clients,phone,' . $client->id,
            'role_id' => 'nullable|exists:mobile_app_roles,id',
            'password' => 'nullable|max:100|min:5',
            'is_active' => 'boolean',
        ]);

        try {
            if ($request->filled('password')) {
                $validated['password'] = bcrypt($request->password);
            } else {
                unset($validated['password']);
            }

            $client->update($validated);

            return response()->json([
                'message' => 'Client updated successfully.',
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during update.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();

            return response()->json([
                'message' => 'Client deleted successfully.',
            ]);
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
            Client::whereIn('id', $validated['indices'])->delete();
            return response()->json(['message' => 'Clients deleted successfully.']);
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
            Client::whereIn('id', $validated['indices'])->update(['is_active' => $validated['value']]);
            return response()->json(['message' => 'Clients status updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass update.'], 500);
        }
    }

    public function ledgers($id)
    {
        $ledgers = ClientLedger::when($id, function ($query) use ($id) {
            $query->where('client_id', $id);
        })
            ->with('client:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($ledgers);
    }
}
