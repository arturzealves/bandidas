<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\MapMarker;
use App\Models\User;
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
        $events = Event::factory()
            ->count(100)
            ->hasSessions(2)
            ->hasPrices(2)
            ->hasArtists(2)
            ->hasPromoters(1, ['user_type' => User::TYPE_PROMOTER])
            ->create();
        
        foreach ($events as $event) {
            MapMarker::factory()->create([
                'latitude' => $event->latitude,
                'longitude' => $event->longitude,
                'user_id' => $event->promoters->first()->id,
            ]);
        }
    }
}
