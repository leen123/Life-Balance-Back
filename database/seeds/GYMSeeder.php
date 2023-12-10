<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GYMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gyms')->insert([
            'user_id' => 2,
            'gym_name' => 'AhmadGYM',
            'location' => 'Damascus',
            'phone' => '0938954497',
            'description' => 'Ahmad GYM',
        ]);
    }
}
