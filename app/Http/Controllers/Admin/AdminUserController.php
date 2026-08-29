<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\UserDataGrid;
use App\Model\Role;
use App\Model\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(UserDataGrid::class)->process();
        }

        $roles = Role::all(['id', 'name']);

        return view('admin::users.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:256',
            'email'     => 'nullable|email|unique:users,email|max:256',
            'phone'     => 'nullable|string|unique:users,phone|max:20',
            'password'     => 'required|max:100|min:5',
            'role_id'   => 'nullable|exists:roles,id|max:10',
            'is_active' => 'boolean',
        ]);

        try {
            $validated['password'] = bcrypt($request->password);

            User::create($validated);

            return response()->json([
                'message' => 'User created successfully.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Encounter error during creating user.',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'      => 'required|string|max:2000',
            'email'     => 'nullable|email|max:256|unique:users,email,' . $user->id,
            'phone'     => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'role_id'   => 'nullable|exists:roles,id|max:10',
            'password'     => 'nullable|max:100|min:5',
            'is_active' => 'boolean',
        ]);

        try {
            if (! empty($validated['password'])) {
                $validated['password'] = bcrypt($request->password);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            return response()->json([
                'message' => 'User updated successfully.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Encounter error during updating user.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'message' => 'User deleted successfully.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Encounter error during deleting user.',
            ], 500);
        }
    }
    public function massDestroy(Request $request)
    {
        $validated = $request->validate([
            'indices' => 'required|array',
        ]);

        try {
            User::whereIn('id', $validated['indices'])->delete();
            return response()->json(['message' => 'Users deleted successfully.']);
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
            User::whereIn('id', $validated['indices'])->update(['is_active' => $validated['value']]);
            return response()->json(['message' => 'Users status updated successfully.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during mass update.'], 500);
        }
    }
}
