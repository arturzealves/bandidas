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
        if (!Schema::hasTable(DatabaseConstants::TABLE_USER_ACCESS_TOKENS)) {
            Schema::create(DatabaseConstants::TABLE_USER_ACCESS_TOKENS, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 512)->unique();
                $table->string('refresh_token', 256)->unique();
                $table->text('abilities')->nullable();
                $table->integer('expires_in')->nullable();
                $table->timestamp('last_used_at')->nullable();
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
        if (Schema::hasTable(DatabaseConstants::TABLE_USER_ACCESS_TOKENS)) {
            Schema::dropIfExists(DatabaseConstants::TABLE_USER_ACCESS_TOKENS);
        }
    }
};
