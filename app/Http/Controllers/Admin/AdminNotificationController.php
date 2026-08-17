<?php

namespace App\Http\Controllers\Admin;

use App\Enums\NotificationEvent;
use App\Jobs\SendFirebaseNotificationJob;
use App\Model\Client;
use App\Model\DeviceToken;
use App\Model\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);

        if ($request->ajax() || $request->wantsJson()) {
            $notifications = Notification::with('client:id,name')
                ->latest('id') // Sorts by ID descending instead of created_at
                ->paginate($perPage);

            return response()->json($notifications);
        }

        // Fetch clients with the token included
        $clients = Client::select('id', 'name', 'fcm_token')->get();
        $eventTypes = NotificationEvent::details();

        $deviceTokens = [];

        return view('admin::notifications.index', compact('deviceTokens', 'clients', 'eventTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|array|min:1', // null means send to all
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|in:' . implode(',', NotificationEvent::values()),
        ]);

        try {
            $tokens = Client::whereIn('id', $validated['client_id'] ?? [])
                ->whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

            SendFirebaseNotificationJob::dispatch(
                'tokens',
                $tokens,
                $validated['title'],
                $validated['body'],
                [
                    'type' => $validated['type'],
                    'created_by' => Auth::guard('admin')->id(),
                    'remark' => "admin",
                    'additional' => notificationAdditionalArrayFormat([
                        'screen' => $validated['type'],
                        'client_ids' => $validated['client_id'] ?? [],
                    ])
                ]
            );
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Encounter error during dispacth notifications.'], 500);
        }

        return response()->json(['message' => 'Notification dispatched successfully.']);
    }
}
