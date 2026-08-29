<?php

namespace App\Http\Controllers\Admin;

use App\Model\ClubStaff;
use App\Model\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminClubStaffController extends Controller
{
    public function index($clubId)
    {
        $staff = ClubStaff::where('club_id', $clubId)->get();
        return response()->json(['staff' => $staff]);
    }

    public function store(Request $request, $clubId)
    {
        $club = Club::findOrFail($clubId);

        $validated = $request->validate([
            'name' => 'required|string|max:256',
            'role' => 'required|string',
            'contact_no' => 'nullable|string|max:256',
            'bio' => 'nullable|string',
            'social_accounts' => 'nullable|string', // Will be JSON string from FormData
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        try {
            if ($request->hasFile('avatar')) {
                $validated['avatar'] = $request->file('avatar')->store('club_staff/avatars');
            }

            if (isset($validated['social_accounts'])) {
                $validated['social_accounts'] = json_decode($validated['social_accounts'], true);
            }

            $validated['club_id'] = $club->id;

            $staff = ClubStaff::create($validated);

            return response()->json([
                'message' => 'Staff member added successfully.',
                'staff' => ClubStaff::where('club_id', $club->id)->get()
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during create.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $staff = ClubStaff::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:2000',
            'role' => 'required|string',
            'contact_no' => 'nullable|string|max:256',
            'bio' => 'nullable|string',
            'social_accounts' => 'nullable|string',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        try {
            if ($request->hasFile('avatar')) {
                if ($staff->avatar && Storage::exists($staff->avatar)) {
                    Storage::delete($staff->avatar);
                }

                $validated['avatar'] = $request->file('avatar')->store('club_staff/avatars');
            } else {
                unset($validated['avatar']);
            }

            if (isset($validated['social_accounts'])) {
                $validated['social_accounts'] = json_decode($validated['social_accounts'], true);
            }

            $staff->update($validated);

            return response()->json([
                'message' => 'Staff member updated successfully.',
                'staff' => ClubStaff::where('club_id', $staff->club_id)->get()
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during update.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $staff = ClubStaff::findOrFail($id);
            $clubId = $staff->club_id;
            
            if ($staff->avatar && Storage::exists($staff->avatar)) {
                Storage::delete($staff->avatar);
            }

            $staff->delete();

            return response()->json([
                'message' => 'Staff member deleted successfully.',
                'staff' => ClubStaff::where('club_id', $clubId)->get()
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during delete.'], 500);
        }
    }
}
