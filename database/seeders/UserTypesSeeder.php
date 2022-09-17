<?php

namespace Database\Seeders;

use App\Models\UserType;
use Database\Mappers\DatabaseConstants;
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
        DB::table(DatabaseConstants::TABLE_USER_TYPES)->insert([
            'name' => UserType::TYPE_USER,
        ]);

        DB::table(DatabaseConstants::TABLE_USER_TYPES)->insert([
            'name' => UserType::TYPE_PROMOTER,
        ]);
        
        DB::table(DatabaseConstants::TABLE_USER_TYPES)->insert([
            'name' => UserType::TYPE_ARTIST,
        ]);
    }
}
