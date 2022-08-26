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
        if (!Schema::hasTable(DatabaseConstants::TABLE_GOOGLE_MAPS_USER_CIRCLES)) {
            Schema::create(DatabaseConstants::TABLE_GOOGLE_MAPS_USER_CIRCLES, function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('name', 100);
                $table->decimal('latitude', 8, 6);
                $table->decimal('longitude', 9, 6);
                $table->mediumInteger('radius');
                $table->char('strokeColor')->nullable();
                $table->decimal('strokeOpacity', 3, 2)->nullable();
                $table->tinyInteger('strokeWeight')->nullable();
                $table->char('fillColor')->nullable();
                $table->decimal('fillOpacity', 3, 2)->nullable();

                $table->timestamps();

                $table->foreign('user_id')->references('id')->on(DatabaseConstants::TABLE_USERS)->onDelete('cascade');
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
        Schema::dropIfExists(DatabaseConstants::TABLE_GOOGLE_MAPS_USER_CIRCLES);
    }
};
