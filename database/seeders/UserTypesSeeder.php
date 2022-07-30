<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_types')->insert([
            'name' => UserType::TYPE_USER,
        ]);

        DB::table('user_types')->insert([
            'name' => UserType::TYPE_PROMOTER,
        ]);
        
        DB::table('user_types')->insert([
            'name' => UserType::TYPE_ARTIST,
        ]);
    }
}
