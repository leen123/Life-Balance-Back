<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::create([
            'name' => 'Hello System',
            'image' => null,
            'userName' => 'hello1',
            'email' => 'SystemAdmin@ai-gym.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'), // password
            'ConfirmPassword' => bcrypt('12345678'), // ConfirmPassword
            'remember_token' => Str::random(10),
            'type' => 1, // system Admin
        ]);

        $user->assignRole('administration');

    }
}
