<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Exceptions\UserNotFoundException;
use App\Jobs\ImportSpotifyUserFollowedArtists;
use App\Models\User;
use App\Models\UserAccessToken;
use App\Models\UserExternalAccount;
use App\Repositories\UserAccessTokenRepository;
use App\Services\SpotifyService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Contracts\User as ContractsUser;
use Laravel\Socialite\Facades\Socialite;

class SpotifyController extends Controller
{
    const REDIRECT_ACTION = 'redirect_action';
    const REDIRECT_ACTION_LOGIN = 'login';
    const REDIRECT_ACTION_REGISTER = 'register';
    const REDIRECT_ACTION_GET_FOLLOWED_ARTISTS = 'getUserFollowedArtists';

    public function redirect($action)
    {
        Session::put(self::REDIRECT_ACTION, $action);

        return Socialite::driver('spotify')
            ->scopes([
                SpotifyService::SCOPE_USER_READ_EMAIL,
                SpotifyService::SCOPE_USER_FOLLOW_READ,
            ])
            ->redirect();
    }

    public function callback(
        UserAccessTokenRepository $userAccessTokenRepository, 
        CreateNewUser $userCreator,
        SpotifyService $service
    ) {
        try {
            $spotifyUser = Socialite::driver('spotify')->user();
    
            $action = Session::get(self::REDIRECT_ACTION);
            Session::forget(self::REDIRECT_ACTION);

            $user = Auth::user();

            if (null === $user) {
                $user = $this->getUserWithExternalAccount($spotifyUser);
            }

            if ($action === self::REDIRECT_ACTION_REGISTER) {
                $user = $this->registerSpotifyUser($spotifyUser, $userCreator);
            } elseif ($action === self::REDIRECT_ACTION_LOGIN) {
                if (null === $user) {
                    throw new UserNotFoundException(__('It seems you are not registered yet'));
                }
                Auth::login($user);
            } else {
                $this->createUserExternalAccount($spotifyUser, $user);
            }

            $this->createUserAccessToken($userAccessTokenRepository, $user, $spotifyUser);

            if ($action == self::REDIRECT_ACTION_GET_FOLLOWED_ARTISTS) {
                ImportSpotifyUserFollowedArtists::dispatch($service, $user);
                return redirect()->to('/artists');
            }
        } catch (UserNotFoundException $e) {
            return redirect('/register')->withErrors(['msg' => $e->getMessage()]);
        }
    
        return redirect('/dashboard');
    }

    protected function registerSpotifyUser(ContractsUser $spotifyUser, CreateNewUser $userCreator)
    {
        $user = $userCreator->create([
            'name' => $spotifyUser->name,
            'email' => $spotifyUser->email,
            'password' => 'useless'.time(),
            'password_confirmation' => 'useless'.time(),
            'terms' => true,
        ]);

        event(new Registered($user));

        $this->createUserExternalAccount($spotifyUser, $user);

        return $user;
    }

    protected function createUserExternalAccount(ContractsUser $spotifyUser, User $user)
    {
        UserExternalAccount::firstOrCreate([
            'external_id' => $spotifyUser->id,
            'user_id' => $user->id,
            'provider_name' => UserExternalAccount::PROVIDER_SPOTIFY,
        ]);
    }

    protected function createUserAccessToken(
        UserAccessTokenRepository $userAccessTokenRepository,
        User $user,
        ContractsUser $spotifyUser
    ) {
        $userAccessTokenRepository->updateOrCreate(
            $user->id,
            UserAccessToken::TOKENABLE_ID_SPOTIFY_ACCESS_TOKEN,
            UserAccessToken::TOKENABLE_TYPE_SPOTIFY_ACCESS_TOKEN,
            UserAccessToken::NAME_SPOTIFY_ACCESS_TOKEN,
            $spotifyUser->accessTokenResponseBody['access_token'],
            $spotifyUser->accessTokenResponseBody['refresh_token'],
            explode(' ', $spotifyUser->accessTokenResponseBody['scope']),
            $spotifyUser->accessTokenResponseBody['expires_in'],
        );
    }

    protected function getUserWithExternalAccount(
        ContractsUser $spotifyUser
    ): ?User {
        $userExternalAccount = UserExternalAccount::where('external_id', $spotifyUser->id)
            ->where('provider_name', UserExternalAccount::PROVIDER_SPOTIFY)
            ->first();

        if ($userExternalAccount !== null) {
            return $userExternalAccount->user;
        }

        return null;
    }
}
