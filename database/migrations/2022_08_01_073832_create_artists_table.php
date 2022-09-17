<?php

use Database\Mappers\DatabaseConstants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable(DatabaseConstants::TABLE_ARTISTS)) {
            Schema::create(DatabaseConstants::TABLE_ARTISTS, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable(DatabaseConstants::TABLE_SPOTIFY_ARTISTS)) {
            Schema::create(DatabaseConstants::TABLE_SPOTIFY_ARTISTS, function (Blueprint $table) {
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

                $table->foreign('artist_id')->references('id')->on(DatabaseConstants::TABLE_ARTISTS)->onDelete('cascade');
            });
        }

        if (!Schema::hasTable(DatabaseConstants::TABLE_GENRES)) {
            Schema::create(DatabaseConstants::TABLE_GENRES, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
            });
        }

        if (!Schema::hasTable(DatabaseConstants::TABLE_ARTIST_GENRE)) {
            Schema::create(DatabaseConstants::TABLE_ARTIST_GENRE, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('artist_id');
                $table->unsignedBigInteger('genre_id');

                $table->foreign('artist_id')->references('id')->on(DatabaseConstants::TABLE_ARTISTS)->onDelete('cascade');
                $table->foreign('genre_id')->references('id')->on(DatabaseConstants::TABLE_GENRES)->onDelete('cascade');
            });
        }

        if (!Schema::hasTable(DatabaseConstants::TABLE_USER_FOLLOWS_ARTISTS)) {
            Schema::create(DatabaseConstants::TABLE_USER_FOLLOWS_ARTISTS, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid('user_id');
                $table->unsignedBigInteger('artist_id');
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on(DatabaseConstants::TABLE_USERS)->onDelete('cascade');
                $table->foreign('artist_id')->references('id')->on(DatabaseConstants::TABLE_ARTISTS)->onDelete('cascade');
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
        if (Schema::hasTable(DatabaseConstants::TABLE_USER_FOLLOWS_ARTISTS)) {
            Schema::dropIfExists(DatabaseConstants::TABLE_USER_FOLLOWS_ARTISTS);
        }
        if (Schema::hasTable(DatabaseConstants::TABLE_ARTIST_GENRE)) {
            Schema::dropIfExists(DatabaseConstants::TABLE_ARTIST_GENRE);
        }
        if (Schema::hasTable(DatabaseConstants::TABLE_GENRES)) {
            Schema::dropIfExists(DatabaseConstants::TABLE_GENRES);
        }
        if (Schema::hasTable(DatabaseConstants::TABLE_SPOTIFY_ARTISTS)) {
            Schema::dropIfExists(DatabaseConstants::TABLE_SPOTIFY_ARTISTS);
        }
        if (Schema::hasTable(DatabaseConstants::TABLE_ARTISTS)) {
            Schema::dropIfExists(DatabaseConstants::TABLE_ARTISTS);
        }
    }
};
