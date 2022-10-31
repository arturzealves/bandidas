<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomepageController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::select([
                'events.uuid',
                'events.name',
                'events.images',
                DB::raw('MIN(event_sessions.start) as start'),
                DB::raw('JSON_ARRAYAGG(artists.name) as artist_name')
            ])
            ->join('event_sessions', 'event_sessions.event_uuid', '=', 'events.uuid')
            ->join('event_artists', 'event_artists.event_uuid', '=', 'events.uuid')
            ->join('artists', 'artists.uuid', '=', 'event_artists.artist_uuid')
            ->where('event_sessions.start', '>=', Carbon::now())
            ->groupBy('events.uuid')
            ->orderBy(DB::raw('MIN(event_sessions.start)'))
            ->paginate(12)
            ->map(function ($event) {
                $event->images = json_decode($event->images, true);
                $event->artist_name = collect(json_decode($event->artist_name))->unique();

                return $event;
            })
        ;

        return view('homepage')
            ->with('events', $events);
    }
}
