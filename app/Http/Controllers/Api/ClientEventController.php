<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Event;
use Illuminate\Http\Request;

class ClientEventController extends Controller
{
    public function index()
    {
        $events = Event::with('club')
            ->where('end_time', '>=', now())
            ->orderBy('start_time')
            ->get();

        return response()->json($events);
    }

    public function show($id)
    {
        $event = Event::with('club')->findOrFail($id);

        return response()->json($event);
    }
}
