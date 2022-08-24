<?php

namespace App\Http\Controllers;

use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Models\UserAccessToken;
use App\Models\UserExternalAccount;
use App\Models\UserType;
use App\Repositories\UserAccessTokenRepository;
use App\Services\SpotifyService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Contracts\User as ContractsUser;
use Laravel\Socialite\Facades\Socialite;

class SpotifyController extends Controller
{
    const REDIRECT_ACTION = 'redirect_action';
    const REDIRECT_ACTION_REGISTER = 'register';

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

    public function callback(UserAccessTokenRepository $userAccessTokenRepository)
    {
        try {
            $spotifyUser = Socialite::driver('spotify')->user();
    
            $user = $this->getUser($spotifyUser, Session::get(self::REDIRECT_ACTION));

            Auth::login($user);

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
        } catch (UserNotFoundException $e) {
            return redirect('/register')->withErrors(['msg' => $e->getMessage()]);
        }
    
        return redirect('/dashboard');
    }

    protected function getUser(ContractsUser $spotifyUser, $redirectAction): User
    {
        $userExternalAccount = UserExternalAccount::where('external_id', $spotifyUser->id)
            ->where('provider_name', UserExternalAccount::PROVIDER_SPOTIFY)
            ->first();

        if ($userExternalAccount !== null) {
            return $userExternalAccount->user;
        }

        if ($redirectAction !== self::REDIRECT_ACTION_REGISTER) {
            throw new UserNotFoundException(__('It seems you are not registered yet'));
        }

        $user = User::updateOrCreate([
            'name' => $spotifyUser->name,
            'email' => $spotifyUser->email,
            'password' => '',
            'user_type_id' => UserType::TYPE_USER_ID
        ]);

        UserExternalAccount::create([
            'external_id' => $spotifyUser->id,
            'user_id' => $user->id,
            'provider_name' => UserExternalAccount::PROVIDER_SPOTIFY,
        ]);
        
        return $user;
    }
}
