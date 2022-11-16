<?php

namespace App\Repositories;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class EventRepository
{
    public function getUpcomingEvents($total = 12): Collection
    {
        return Event::select([
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
            ->limit($total)
            ->get()
            ->map(function ($event) {
                $event->images = json_decode($event->images, true);
                $event->artist_name = collect(json_decode($event->artist_name))->unique();

                return $event;
            })
        ;
    }
}
