<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\UserDataGrid;
use App\Model\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(UserDataGrid::class)->process();
        }

        return view('admin::users.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string|unique:users,phone',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        User::create($validated);

        return response()->json([
            'message' => 'User created successfully.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|unique:users,phone,' . $user->id,
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'User updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id); 

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
