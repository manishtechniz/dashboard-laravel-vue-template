<?php

namespace App\Http\Controllers\Admin;

use App\Model\Client;
use App\Model\DeviceToken;
use App\Model\Notification;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('client')->latest()->get();

        // Fetch clients with the token included
        $clients = Client::select('id', 'name', 'fcm_token')->get();
        $deviceTokens = $clients->whereNotNull('fcm_token')
            ->where('fcm_token', '!=', '')
            ->toArray();

        // dd($deviceTokens);

        return view('admin::notifications.index', compact('notifications', 'deviceTokens', 'clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id', // null means send to all
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string',
        ]);

        if ($validated['client_id']) {
            Notification::create($validated);
            // Simulate FCM push to client's device tokens
            $tokens = DeviceToken::where('client_id', $validated['client_id'])->pluck('token');
            // Log push
        } else {
            // Broadcast to all clients
            $clients = Client::all();
            foreach ($clients as $client) {
                Notification::create(array_merge($validated, ['client_id' => $client->id]));
            }
        }

        return response()->json(['message' => 'Notification dispatched successfully.']);
    }
}
