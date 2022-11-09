<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Event $event)
    {
        return view('events.show')->with([
            'event' => $event,
            'images' => json_decode($event->images, true),
        ]);
    }
}
