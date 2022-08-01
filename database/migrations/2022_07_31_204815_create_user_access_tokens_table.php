<?php

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
        if (!Schema::hasTable('user_access_tokens')) {
            Schema::create('user_access_tokens', function (Blueprint $table) {
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

                $table->foreign('user_id')->references('id')->on('users');
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
        if (Schema::hasTable('user_access_tokens')) {
            Schema::dropIfExists('user_access_tokens');
        }
    }
};
