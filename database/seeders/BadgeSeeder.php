<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use HaydenPierce\ClassFinder\ClassFinder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = ClassFinder::getClassesInNamespace('App\Gamify\Badges');
        foreach ($classes as $class) {
            new $class();
        }
    }
}
