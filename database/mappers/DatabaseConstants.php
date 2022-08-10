<?php

namespace Database\Mappers;

final class DatabaseConstants
{
    const TABLE_USERS = 'users';
    const TABLE_PASSWORD_RESETS = 'password_resets';
    const TABLE_FAILED_JOBS = 'failed_jobs';
    const TABLE_PERSONAL_ACCESS_TOKENS = 'personal_access_tokens';
    const TABLE_SESSIONS = 'sessions';
    const TABLE_USER_TYPES = 'user_types';
    const TABLE_USER_ACCESS_TOKENS = 'user_access_tokens';
    const TABLE_ARTISTS = 'artists';
    const TABLE_SPOTIFY_ARTISTS = 'spotify_artists';
    const TABLE_GENRES = 'genres';
    const TABLE_ARTIST_HAS_GENRES = 'artist_has_genres';
    const TABLE_USER_FOLLOWS_ARTISTS = 'user_follows_artists';
    const TABLE_GOOGLE_MAPS_USER_CIRCLES = 'google_maps_user_circles';
}
