<?php

use App\Http\Controllers\ArtistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', 'App\Http\Controllers\DashboardController@dashboard')->name('dashboard');
    
    Route::get('/spotify/auth', 'App\Http\Controllers\SpotifyController@auth')->name('spotify.auth');
    Route::get('/spotify/callback', 'App\Http\Controllers\SpotifyController@callback')->name('spotify.callback');
    Route::get('/spotify', 'App\Http\Controllers\SpotifyController@spotify')->name('spotify');

    Route::resource('artists', ArtistController::class)->only(['index', 'show']);
});
