<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::factory()
            ->count(10)
            ->hasSessions(2)
            ->hasPrices(2)
            ->hasArtists(2)
            ->hasPromoters(1)
            ->create();
    }
}
