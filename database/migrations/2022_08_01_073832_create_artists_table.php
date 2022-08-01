<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const TABLE_ARTISTS = 'artists';
    const TABLE_SPOTIFY_ARTISTS = 'spotify_artists';
    const TABLE_GENRES = 'genres';
    const TABLE_ARTIST_HAS_GENRES = 'artist_has_genres';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable(self::TABLE_ARTISTS)) {
            Schema::create(self::TABLE_ARTISTS, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable(self::TABLE_SPOTIFY_ARTISTS)) {
            Schema::create(self::TABLE_SPOTIFY_ARTISTS, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('artist_id');
                $table->string('spotify_id');
                $table->string('name');
                $table->string('uri');
                $table->json('images');
                $table->string('href');
                $table->integer('followers');
                $table->integer('popularity');
                $table->json('external_urls');
                $table->timestamps();

                $table->foreign('artist_id')->references('id')->on(self::TABLE_ARTISTS);
            });
        }

        if (!Schema::hasTable(self::TABLE_GENRES)) {
            Schema::create(self::TABLE_GENRES, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
            });
        }

        if (!Schema::hasTable(self::TABLE_ARTIST_HAS_GENRES)) {
            Schema::create(self::TABLE_ARTIST_HAS_GENRES, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('artist_id');
                $table->unsignedBigInteger('genre_id');

                $table->foreign('artist_id')->references('id')->on(self::TABLE_ARTISTS);
                $table->foreign('genre_id')->references('id')->on(self::TABLE_GENRES);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable(self::TABLE_ARTIST_HAS_GENRES)) {
            Schema::dropIfExists(self::TABLE_ARTIST_HAS_GENRES);
        }
        if (Schema::hasTable(self::TABLE_GENRES)) {
            Schema::dropIfExists(self::TABLE_GENRES);
        }
        if (Schema::hasTable(self::TABLE_SPOTIFY_ARTISTS)) {
            Schema::dropIfExists(self::TABLE_SPOTIFY_ARTISTS);
        }
        if (Schema::hasTable(self::TABLE_ARTISTS)) {
            Schema::dropIfExists(self::TABLE_ARTISTS);
        }
    }
};
