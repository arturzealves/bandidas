<?php

use Database\Mappers\DatabaseConstants;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReputationFieldOnUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(DatabaseConstants::TABLE_USERS, function (Blueprint $table) {
            $table->unsignedInteger('reputation')->default(0)->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(DatabaseConstants::TABLE_USERS, function (Blueprint $table) {
            $table->dropColumn('reputation');
        });
    }
}
