<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
  public function index()
  {
    return view('admin::auth.login');
  }

  public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember' => 'nullable',
        ]);

        try {
            $email = $request->email;
            $password = $request->password;
            $rememberMe = $request->remember_me;

            $user = Admin::where('email', $email)->first();

            if (!$user || !Hash::check($password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials'
                ], 404);
            }  

            if (Auth::guard('admin')->attempt([
                'email' => $email,
                'password' => $password,
            ], ! empty($rememberMe))) {
                // request()->session()->regenerate(); 

                return response()->json([
                    'message' => 'Login successful',
                    'redirectTo' => session('redirectTo', route('admin.dashboard')),
                ]);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }
}
