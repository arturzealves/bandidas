<?php

use App\Http\Controllers\ArtistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\UserController;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;

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

// Routes for authenticated and verified users
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', 'App\Http\Controllers\DashboardController@dashboard')->name('dashboard');
    Route::resource('artists', ArtistController::class)->only(['index', 'show']);
    Route::resource('users', UserController::class)->only(['show'])->scoped(['user' => 'username']);
});

// Routes for verified promoters
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'user.is.promoter'
])->group(function () {
    Route::get('/map', 'App\Http\Controllers\Promoter\MapController@index')->name('map');
});

// Public routes
Route::get('/auth/spotify/redirect/{action}', [SpotifyController::class, 'redirect'])->name('spotify.redirect');
Route::get('/auth/spotify/callback', [SpotifyController::class, 'callback'])->name('spotify.callback');

Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    $verificationLimiter = config('fortify.limiters.verification', '6,1');
    
    // Email Verification...
    if (Features::enabled(Features::emailVerification())) {
        Route::get('/email/verify/{uuid}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'signed', 'throttle:'.$verificationLimiter])
            ->name('verification.verify');
    }
});
