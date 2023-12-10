<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'image' => null,
                'name' => 'Hello User1',
                'userName' => 'hello2',
                'email' => 'GymManager@ai-gym.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'), // password
                'ConfirmPassword' => bcrypt('12345678'), // ConfirmPassword
                'remember_token' => Str::random(10),
                'type' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image' => null,
                'name' => 'Hello User',
                'userName' => 'hello3',
                'email' => 'GymPalyer@ai-gym.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'), // password
                'ConfirmPassword' => bcrypt('12345678'), // ConfirmPassword
                'remember_token' => Str::random(10),
                'type' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
