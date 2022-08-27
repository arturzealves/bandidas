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
        if (!Schema::hasTable(DatabaseConstants::TABLE_GOOGLE_MAPS_USER_CIRCLES_HAS_ARTISTS)) {
            Schema::create(DatabaseConstants::TABLE_GOOGLE_MAPS_USER_CIRCLES_HAS_ARTISTS, function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('google_maps_user_circle_id');
                $table->unsignedBigInteger('artist_id');
                $table->decimal('budget', 7, 2)->nullable();
                $table->timestamps();

                $table->foreign('google_maps_user_circle_id', 'gm_user_circle_id')
                    ->references('id')
                    ->on(DatabaseConstants::TABLE_GOOGLE_MAPS_USER_CIRCLES)
                    ->onDelete('cascade');

                $table->foreign('artist_id')
                    ->references('id')
                    ->on(DatabaseConstants::TABLE_ARTISTS)
                    ->onDelete('cascade');
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
        Schema::dropIfExists(DatabaseConstants::TABLE_GOOGLE_MAPS_USER_CIRCLES_HAS_ARTISTS);
    }
};
