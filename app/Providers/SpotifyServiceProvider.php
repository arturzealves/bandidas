<?php

namespace App\Providers;

use App\Services\SpotifyService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use SpotifyWebAPI\Session;

class SpotifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Session::class, function () {
            return new Session(
                Config::get('services.spotify.id'),
                Config::get('services.spotify.secret'),
                Config::get('services.spotify.redirect')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
