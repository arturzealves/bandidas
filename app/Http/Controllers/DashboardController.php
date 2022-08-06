<?php

namespace App\Http\Controllers;

use App\Models\SpotifyArtist;
use App\Models\UserType;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard(Request $request, UserRepository $userRepository)
    {
        $user = Auth::user();

        $viewVariables = [
            'user' => $user,
        ];

        if ($user->type->name == UserType::TYPE_PROMOTER) {
            $viewVariables += [
                'userCount' => $userRepository->getCountByUserTypeId(UserType::TYPE_USER_ID),
                'artistCount' => $userRepository->getCountByUserTypeId(UserType::TYPE_ARTIST_ID),
                'spotifyArtistCount' => SpotifyArtist::count(),
            ];
        }

        return view(sprintf('dashboard/%s', $user->type->name))
            ->with($viewVariables);
    }
}
