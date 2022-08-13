<?php

namespace App\Services;

use App\Exceptions\SpotifyAccessTokenException;
use App\Exceptions\SpotifyInvalidStateException;
use App\Exceptions\UserAccessTokenNotFoundException;
use App\Models\User;
use App\Models\UserAccessToken;
use App\Repositories\UserAccessTokenRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\Session;

class SpotifyService
{
    private $userAccessTokenRepository;
    private $api;
    
    public function __construct(UserAccessTokenRepository $userAccessTokenRepository, SpotifyWebAPI $api)
    {
        $this->userAccessTokenRepository = $userAccessTokenRepository;
        $this->api = $api;
    }

    public function authenticate(Session $session): string
    {
        $state = $session->generateState();

        Cache::put('spotifyState', $state);
        
        $options = [
            'scope' => [
                'user-read-private',
                'user-read-email',
                'user-follow-read',
            ],
            'show_dialog' => true,
            'state' => $state,
        ];

        return $session->getAuthorizeUrl($options);
    }

    public function callback(Session $session, User $user, $code, $state): void
    {
        // Fetch the stored state value from somewhere. A session for example
        if ($state !== Cache::get('spotifyState')) {
            // The state returned isn't the same as the one we've stored, we shouldn't continue
            throw new SpotifyInvalidStateException();
        }
        
        if (!$session->requestAccessToken($code)) {
            throw new SpotifyAccessTokenException();
        }

        UserAccessToken::firstOrCreate(
            [
                'user_id' => $user->id,
                'tokenable_id' => UserAccessToken::TOKENABLE_ID_SPOTIFY_ACCESS_TOKEN
            ],
            [
                'user_id' => $user->id,
                'tokenable_type' => UserAccessToken::TOKENABLE_TYPE_SPOTIFY_ACCESS_TOKEN,
                'tokenable_id' => UserAccessToken::TOKENABLE_ID_SPOTIFY_ACCESS_TOKEN,
                'name' => UserAccessToken::NAME_SPOTIFY_ACCESS_TOKEN,
                'token' => $session->getAccessToken(),
                'refresh_token' => $session->getRefreshToken(),
                'abilities' => json_encode($session->getScope()),
                'expires_in' => $session->getTokenExpiration(),
            ]
        );
    }

    public function getUserFollowedArtists(User $user): array
    {
        $userAccessToken = $this->userAccessTokenRepository->getOneByUserIdAndTokenableId(
            $user->id,
            UserAccessToken::TOKENABLE_ID_SPOTIFY_ACCESS_TOKEN
        );

        if (empty($userAccessToken)) {
            throw new UserAccessTokenNotFoundException();
        }

        $options = [
            'limit' => 50,
        ];

        $artists = [];
        
        do {
            $shouldContinue = false;

            $key = sprintf(
                'spotify_user_%s_following_%s',
                $user->id,
                hash('sha256', json_encode($options))
            );

            if (Cache::has($key)) {
                $response = Cache::get($key);
            } else {
                $this->api->setAccessToken($userAccessToken->token);
        
                $response = $this->api->getUserFollowedArtists($options);

                $userAccessToken->last_used_at = Carbon::now();
                $userAccessToken->save();
    
                $oneWeekInSeconds = 604800;
                Cache::put($key, $response, $oneWeekInSeconds);
            }
    
            foreach ($response->artists->items as $artist) {
                $artists[] = $artist;
            }

            if (!empty($response->artists->next)) {
                $options['after'] = $response->artists->cursors->after;
                $shouldContinue = true;
            }
        } while ($shouldContinue);

        return $artists;
    }
}
