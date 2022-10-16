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
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyService
{
    const SCOPE_USER_READ_EMAIL = 'user-read-email';
    const SCOPE_USER_FOLLOW_READ = 'user-follow-read';

    private $api;
    private $session;
    private $userAccessTokenRepository;
    
    public function __construct(
        SpotifyWebAPI $api,
        Session $session,
        UserAccessTokenRepository $userAccessTokenRepository
    ) {
        $this->api = $api;
        $this->api->setSession($session);

        $this->session = $session;
        $this->userAccessTokenRepository = $userAccessTokenRepository;

        // $options = [
        //     'auto_refresh' => true,
        // ];
    }

    /**
     * @throws SpotifyWebAPIException
     * @throws SpotifyWebAPIAuthException
     * @throws SpotifyInvalidStateException
     * @throws SpotifyAccessTokenException
     */
    public function validateCallback($code, $state): void
    {
        // Fetch the stored state value from somewhere. A session for example
        if ($state !== Cache::get('spotifyState')) {
            // The state returned isn't the same as the one we've stored, we shouldn't continue
            throw new SpotifyInvalidStateException();
        }
        
        if (!$this->session->requestAccessToken($code)) {
            throw new SpotifyAccessTokenException();
        }
    }

    public function getSession()
    {
        return $this->session;
    }

    /**
     * @throws SpotifyWebAPIException
     * @throws SpotifyWebAPIAuthException
     */
    public function getUserProfile()
    {
        $accessToken = $this->getSession()->getAccessToken();
        $this->api->setAccessToken($accessToken);
        
        return $this->api->me();
    }

    /**
     * @throws SpotifyWebAPIException
     * @throws SpotifyWebAPIAuthException
     */
    public function getUserFollowedArtists(User $user): array
    {
        $userAccessToken = $this->userAccessTokenRepository->getOneByUserUuidAndTokenableId(
            $user->uuid,
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
                $user->uuid,
                hash('sha256', json_encode($options))
            );

            if (Cache::has($key)) {
                $response = Cache::get($key);
            } else {
                $this->session->setAccessToken($userAccessToken->token);
                $this->session->setRefreshToken($userAccessToken->refresh_token);

                // $this->session->refreshAccessToken($userAccessToken->refresh_token);

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
