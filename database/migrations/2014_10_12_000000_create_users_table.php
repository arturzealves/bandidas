<?php

use App\Models\User;
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
        if (!Schema::hasTable(DatabaseConstants::TABLE_USERS)) {
            Schema::create(DatabaseConstants::TABLE_USERS, function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->string('username')->unique();
                $table->string('email')->unique();
                $table->enum('user_type', [
                    User::TYPE_USER,
                    User::TYPE_PROMOTER,
                    User::TYPE_ARTIST,
                ]);
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->foreignId('current_team_id')->nullable();
                $table->string('profile_photo_path', 2048)->nullable();
                $table->timestamps();
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
        Schema::dropIfExists(DatabaseConstants::TABLE_USERS);
    }
};
