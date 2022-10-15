<?php

use App\Models\Event;
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
        if (!Schema::hasTable(DatabaseConstants::TABLE_ADDRESSES)) {
            Schema::create(DatabaseConstants::TABLE_ADDRESSES, function (Blueprint $table) {
                $table->uuid()->primary();
                $table->string('line1');
                $table->string('line2')->nullable();
                $table->string('line3')->nullable();
                $table->string('line4')->nullable();
                $table->string('city');
                $table->string('region');
                $table->string('postal_code');
                $table->string('country');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable(DatabaseConstants::TABLE_EVENTS)) {
            Schema::create(DatabaseConstants::TABLE_EVENTS, function (Blueprint $table) {
                $table->uuid()->primary();
                $table->string('name');
                $table->enum('type', [
                    Event::TYPE_MUSIC,
                ]);
                $table->json('images');
                $table->longText('description');
                $table->decimal('latitude', 8, 6);
                $table->decimal('longitude', 9, 6);
                $table->uuid('address_uuid')->nullable();
                $table->smallInteger('min_age')->nullable();

                $table->timestamps();

                $table->foreign('address_uuid')
                    ->references('uuid')
                    ->on(DatabaseConstants::TABLE_ADDRESSES)
                    ->onDelete('cascade');
            });
        }

        if (!Schema::hasTable(DatabaseConstants::TABLE_EVENT_SESSIONS)) {
            Schema::create(DatabaseConstants::TABLE_EVENT_SESSIONS, function (Blueprint $table) {
                $table->uuid()->primary();
                $table->uuid('event_uuid');
                $table->datetime('start');
                $table->datetime('end');
                $table->timestamps();

                $table->foreign('event_uuid')
                    ->references('uuid')
                    ->on(DatabaseConstants::TABLE_EVENTS)
                    ->onDelete('cascade');
            });
        }

        if (!Schema::hasTable(DatabaseConstants::TABLE_EVENT_PRICES)) {
            Schema::create(DatabaseConstants::TABLE_EVENT_PRICES, function (Blueprint $table) {
                $table->uuid()->primary();
                $table->uuid('event_uuid');
                $table->decimal('price', 7, 2);
                $table->datetime('date')->nullable();
                $table->smallInteger('age')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();

                $table->foreign('event_uuid')
                    ->references('uuid')
                    ->on(DatabaseConstants::TABLE_EVENTS)
                    ->onDelete('cascade');
            });
        }

        if (!Schema::hasTable(DatabaseConstants::TABLE_EVENT_ARTISTS)) {
            Schema::create(DatabaseConstants::TABLE_EVENT_ARTISTS, function (Blueprint $table) {
                $table->primary(['event_uuid', 'artist_id']);
                $table->uuid('event_uuid');
                $table->unsignedBigInteger('artist_id');

                $table->foreign('event_uuid')
                    ->references('uuid')
                    ->on(DatabaseConstants::TABLE_EVENTS)
                    ->onDelete('cascade');

                $table->foreign('artist_id')
                    ->references('id')
                    ->on(DatabaseConstants::TABLE_ARTISTS)
                    ->onDelete('cascade');
            });
        }

        if (!Schema::hasTable(DatabaseConstants::TABLE_EVENT_PROMOTERS)) {
            Schema::create(DatabaseConstants::TABLE_EVENT_PROMOTERS, function (Blueprint $table) {
                $table->primary(['event_uuid', 'promoter_uuid']);
                $table->uuid('event_uuid');
                $table->uuid('promoter_uuid');

                $table->foreign('event_uuid')
                    ->references('uuid')
                    ->on(DatabaseConstants::TABLE_EVENTS)
                    ->onDelete('cascade');

                $table->foreign('promoter_uuid')
                    ->references('id')
                    ->on(DatabaseConstants::TABLE_USERS)
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
        Schema::dropIfExists(DatabaseConstants::TABLE_EVENT_ARTISTS);
        Schema::dropIfExists(DatabaseConstants::TABLE_EVENT_PROMOTERS);
        Schema::dropIfExists(DatabaseConstants::TABLE_EVENT_SESSIONS);
        Schema::dropIfExists(DatabaseConstants::TABLE_EVENT_PRICES);
        Schema::dropIfExists(DatabaseConstants::TABLE_EVENTS);
        Schema::dropIfExists(DatabaseConstants::TABLE_ADDRESSES);
    }
};
