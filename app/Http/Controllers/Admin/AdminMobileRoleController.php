<?php

namespace App\Http\Controllers\Admin;

use App\Model\MobileAppRole;
use Illuminate\Http\Request;

class AdminMobileRoleController extends Controller
{
    public function index()
    {
        $roles = MobileAppRole::all();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'data' => $roles,
            ]);
        }

        return view('admin::mobile_roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:mobile_app_roles,name',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'route_permissions' => 'nullable|array',
            'type'        => 'nullable|string',
        ]);

        $validated['permissions'] = $validated['permissions'] ?? [];
        $validated['route_permissions'] = $validated['route_permissions'] ?? [];
        $validated['type']        = $validated['type'] ?? 'custom';

        $role = MobileAppRole::create($validated);

        return response()->json([
            'message' => 'Mobile App Role created successfully.',
            'data'    => $role,
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = MobileAppRole::findOrFail($id);

        if ($role->type === 'system') {
            return response()->json([
                'message' => 'System roles cannot be modified.',
            ], 422);
        }

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255|unique:mobile_app_roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'route_permissions' => 'nullable|array',
        ]);

        $role->update($validated);

        return response()->json([
            'message' => 'Mobile App Role updated successfully.',
            'data'    => $role,
        ]);
    }

    public function destroy($id)
    {
        $role = MobileAppRole::findOrFail($id);

        if ($role->type === 'system') {
            return response()->json([
                'message' => 'System roles cannot be deleted.',
            ], 422);
        }

        $role->delete();

        return response()->json([
            'message' => 'Mobile App Role deleted successfully.',
        ]);
    }
}
