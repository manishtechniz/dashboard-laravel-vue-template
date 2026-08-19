<?php

namespace App\Http\Controllers\Supabase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supabase\ClubTable;

class ClubTableController extends Controller
{
    public function update(Request $request, $id)
    {
        $masterKey = 'admin';

        $data = $request->validate([
            'field' => 'required|string',
            'value' => 'nullable',
            'password' => 'nullable|string'
        ]);

        $table = ClubTable::find($id);

        if (!$table) {
            return response()->json(['error' => 'Table not found'], 404);
        }

        if ($table->is_locked) {
            if (empty($data['password']) || ($data['password'] !== $table->lock_password && $data['password'] !== $masterKey)) {
                return response()->json(['error' => 'Table is locked. Invalid password.'], 403);
            }
        }

        $field = $data['field'];
        $value = $data['value'];

        if ($field === 'bill_amount') {
            $value = (float) $value;
            if ($value < 0) {
                return response()->json(['error' => 'Bill amount cannot be negative.'], 422);
            }
        }
        
        if ($field === 'guest_name' && !empty($value)) {
            $value = strip_tags($value);
        }

        $table->update([
            $field => $value
        ]);

        return response()->json(['message' => 'Updated successfully', 'value' => $value]);
    }

    public function toggleLock(Request $request, $id)
    {
        $masterKey = 'admin';
        
        $data = $request->validate([
            'action' => 'required|in:lock,unlock',
            'password' => 'required|string',
            'user_name' => 'nullable|string'
        ]);

        $table = ClubTable::find($id);

        if (!$table) {
            return response()->json(['error' => 'Table not found'], 404);
        }

        if ($data['action'] === 'lock') {
            $table->update([
                'is_locked' => true,
                'lock_password' => $data['password'],
                'locked_by_name' => $data['user_name'] ?? 'Unknown'
            ]);
        } else {
            if ($table->is_locked && $data['password'] !== $table->lock_password && $data['password'] !== $masterKey) {
                return response()->json(['error' => 'Invalid password to unlock'], 403);
            }
            $table->update([
                'is_locked' => false,
                'lock_password' => null,
                'locked_by_name' => null
            ]);
        }

        return response()->json(['message' => 'Lock toggled']);
    }
}
