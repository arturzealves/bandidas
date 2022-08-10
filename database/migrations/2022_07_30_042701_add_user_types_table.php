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
        if (!Schema::hasTable(DatabaseConstants::TABLE_USER_TYPES)) {
            Schema::create(DatabaseConstants::TABLE_USER_TYPES, function (Blueprint $table) {
                $table->id();
                $table->string('name');
            });
        }

        if (!Schema::hasColumn(DatabaseConstants::TABLE_USERS, 'user_type_id')) {
            Schema::table(DatabaseConstants::TABLE_USERS, function (Blueprint $table) {
                $table->unsignedBigInteger('user_type_id')->after('email')->nullable();

                $table->foreign('user_type_id')->references('id')->on(DatabaseConstants::TABLE_USER_TYPES);
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
        if (Schema::hasColumn(DatabaseConstants::TABLE_USERS, 'user_type_id')) {
            Schema::table(DatabaseConstants::TABLE_USERS, function (Blueprint $table) {
                $table->dropForeign(['user_type_id']);

                $table->dropColumn('user_type_id');
            });
        }
        
        if (Schema::hasTable(DatabaseConstants::TABLE_USER_TYPES)) {
            Schema::dropIfExists(DatabaseConstants::TABLE_USER_TYPES);
        }
    }
};
