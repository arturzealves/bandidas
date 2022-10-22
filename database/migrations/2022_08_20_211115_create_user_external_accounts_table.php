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
        if (!Schema::hasTable(DatabaseConstants::TABLE_USER_EXTERNAL_ACCOUNTS)) {
            Schema::create(DatabaseConstants::TABLE_USER_EXTERNAL_ACCOUNTS, function (Blueprint $table) {
                $table->uuid()->primary();
                $table->uuid('user_id');
                $table->string('external_id', 100);
                $table->string('provider_name', 50);
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
        Schema::dropIfExists(DatabaseConstants::TABLE_USER_EXTERNAL_ACCOUNTS);
    }
};
