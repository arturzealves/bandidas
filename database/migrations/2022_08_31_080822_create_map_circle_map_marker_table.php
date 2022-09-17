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
        if (!Schema::hasTable(DatabaseConstants::TABLE_MAP_CIRCLE_MAP_MARKER)) {
            Schema::create(DatabaseConstants::TABLE_MAP_CIRCLE_MAP_MARKER, function (Blueprint $table) {
                $table->id();
                $table->uuid('map_circle_id');
                $table->uuid('map_marker_id');
                $table->mediumInteger('distance');
                $table->timestamps();

                $table->foreign('map_circle_id')
                    ->references('id')
                    ->on(DatabaseConstants::TABLE_MAP_CIRCLES)
                    ->onDelete('cascade');

                $table->foreign('map_marker_id')
                    ->references('id')
                    ->on(DatabaseConstants::TABLE_MAP_MARKERS)
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
        Schema::dropIfExists(DatabaseConstants::TABLE_MAP_CIRCLE_MAP_MARKER);
    }
};
