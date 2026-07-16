<?php
namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
  public function index()
  {
    $admin = Auth::guard('admin')->user() ?? null; 

    return view('admin::profile.index', compact('admin'));
  }

public function update(Request $request)
{

    // dd($request->file(), $request->all());
    $id = Auth::guard('admin')->id();  

    // 1. Validate the incoming request
    $validatedData = $request->validate([
        'name'      => 'nullable|string|max:255',
        'email'     => 'nullable|email|max:255|unique:users,email,' . $id,
        'phone'     => 'nullable|string|max:25',
        'avatar'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'role_id'   => 'nullable|exists:roles,id',
        'user_type' => 'nullable|string|max:50',
        'is_active' => 'nullable|boolean',
        'password'  => 'nullable|required_with:current_password|string|min:8', 
        'password_confirmation' => 'nullable|required_with:password|string|min:8|same:password',
        'current_password' => 'nullable|string|min:8|required_with:password'
    ]);

    try {
        // 2. Find the specific record
        $user = Admin::findOrFail($id);

        // 3. Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar from storage if it exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Store new avatar and update the data array with the path
            $validatedData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // 4. Handle Password Hashing
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);

            if (!Hash::check($validatedData['current_password'], $user->password)) {
                return response()->json(
                   create422ErrorFormat('current_password', 'Current password is incorrect.')
                , 422);
            }
        } else {
            // If password is left blank, remove it from the array so we don't overwrite the existing one
            unset($validatedData['password']);
        }

        unset($validatedData['password_confirmation'], $validatedData['current_password']);

        // 5. Handle Checkbox Boolean (HTML forms don't send unchecked boxes)
        $validatedData['is_active'] = $request->has('is_active');

        // 6. Update the record
        $user->update($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully' 
        ]); 

    } catch (\Exception $e) { 
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while updating the record. Please try again.'
        ], 500);
    }
}

  
}
