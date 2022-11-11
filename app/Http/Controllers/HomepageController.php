<?php

namespace App\Http\Controllers;

use App\Repositories\ArtistRepository;
use App\Repositories\EventRepository;

class HomepageController extends Controller
{
    public function index(EventRepository $eventRepository, ArtistRepository $artistRepository)
    {
        return view('homepage')
            ->with([
                'events' => $eventRepository->getUpcomingEvents(),
                'artists' => $artistRepository->getRandomSpotifyArtistsWithImages(),
            ])
        ;
    }
}
