<?php

use Database\Mappers\DatabaseConstants;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamifyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // reputations table
        Schema::create(DatabaseConstants::TABLE_REPUTATIONS, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->mediumInteger('point', false)->default(0);
            $table->uuid('subject_id')->nullable();
            $table->string('subject_type')->nullable();
            $table->uuid('payee_id')->nullable();
            $table->text('meta')->nullable();
            $table->timestamps();
        });

        // badges table
        Schema::create(DatabaseConstants::TABLE_BADGES, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('icon')->nullable();
            $table->tinyInteger('level')->default(config('gamify.badge_default_level', 1));
            $table->timestamps();
        });

        // user_badges pivot
        Schema::create(DatabaseConstants::TABLE_USER_BADGES, function (Blueprint $table) {
            $table->primary(['user_id', 'badge_id']);
            $table->uuid('user_id');
            $table->unsignedInteger('badge_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(DatabaseConstants::TABLE_USER_BADGES);
        Schema::dropIfExists(DatabaseConstants::TABLE_BADGES);
        Schema::dropIfExists(DatabaseConstants::TABLE_REPUTATIONS);
    }
}
