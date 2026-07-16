<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\DeviceToken;
use Illuminate\Http\Request;

class ClientNotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()->get();

        return response()->json($notifications);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->update(['read_at' => now()]);

        return response()->json(['message' => 'Notification marked as read.']);
    }

    public function storeToken(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'device_type' => 'required|string|in:ios,android,web',
        ]);

        DeviceToken::updateOrCreate(
            ['client_id' => $request->user()->id, 'token' => $validated['token']],
            ['device_type' => $validated['device_type']]
        );

        return response()->json(['message' => 'Device token registered successfully.']);
    }
}
