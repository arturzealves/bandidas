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
        if (!Schema::hasTable('user_types')) {
            Schema::create('user_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
            });
        }

        if (!Schema::hasColumn('users', 'user_type_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('user_type_id')->after('email')->nullable();

                $table->foreign('user_type_id')->references('id')->on('user_types');
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
        if (Schema::hasColumn('users', 'user_type_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['user_type_id']);

                $table->dropColumn('user_type_id');
            });
        }
        
        if (Schema::hasTable('user_types')) {
            Schema::dropIfExists('user_types');
        }
    }
};
