<?php

namespace App\Http\Controllers;

use App\Models\SpotifyArtist;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class DashboardController extends Controller
{
    public function dashboard(Request $request, UserRepository $userRepository)
    {
        $user = Auth::user();

        $viewVariables = [
            'user' => $user,
        ];

        if ($user->user_type == User::TYPE_PROMOTER) {
            $viewVariables += [
                'userCount' => $userRepository->getCountByUserType(User::TYPE_USER),
                'artistCount' => $userRepository->getCountByUserType(User::TYPE_ARTIST),
                'spotifyArtistCount' => SpotifyArtist::count(),
            ];
        }

        $cookie = Cookie::make('userUuid', $user->uuid, 10, null, null, false, false);
        Cookie::queue($cookie);

        return view(sprintf('dashboard/%s', $user->user_type))
            ->with($viewVariables);
    }
}
