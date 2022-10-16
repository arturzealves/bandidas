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
                $table->uuid();
                $table->uuid('map_circle_uuid');
                $table->uuid('map_marker_uuid');
                $table->mediumInteger('distance');
                $table->timestamps();

                $table->foreign('map_circle_uuid')
                    ->references('uuid')
                    ->on(DatabaseConstants::TABLE_MAP_CIRCLES)
                    ->onDelete('cascade');

                $table->foreign('map_marker_uuid')
                    ->references('uuid')
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
