<?php

use App\Http\Controllers\ArtistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;

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
    Route::resource('artists', ArtistController::class)->only(['index', 'show']);
});

// Route::middleware('guest')->group(function () {
// });

Route::get('/auth/spotify/redirect/{action}', [SpotifyController::class, 'redirect'])->name('spotify.redirect');
Route::get('/auth/spotify/callback', [SpotifyController::class, 'callback'])->name('spotify.callback');
