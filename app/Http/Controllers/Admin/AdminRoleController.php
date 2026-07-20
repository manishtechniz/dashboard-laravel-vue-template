<?php

namespace App\Http\Controllers\Admin;

use App\Model\Role;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();  

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'data' => $roles,
            ]);
        }

        return view('admin::roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'type'        => 'nullable|string',
        ]);

        $validated['permissions'] = $validated['permissions'] ?? [];
        $validated['type']        = $validated['type'] ?? 'custom';

        $role = Role::create($validated);

        return response()->json([
            'message' => 'Role created successfully.',
            'data'    => $role,
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if ($role->type === 'system') {
            return response()->json([
                'message' => 'System roles cannot be modified.',
            ], 422);
        }

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role->update($validated);

        return response()->json([
            'message' => 'Role updated successfully.',
            'data'    => $role,
        ]);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->type === 'system') {
            return response()->json([
                'message' => 'System roles cannot be deleted.',
            ], 422);
        }

        $role->delete();

        return response()->json([
            'message' => 'Role deleted successfully.',
        ]);
    }
}

