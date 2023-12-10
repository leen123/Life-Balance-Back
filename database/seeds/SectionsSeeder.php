<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
class SectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sections')->insert([
            [
                'id' => 1,
                'name' => 'Social',
                'code' => 'section-social',
                'icon' => 'test.png',
                'image' => 'emo_1637260165.png',
                'description' => 'this section for social',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ],
            [
                'id'  => 2,
                'name' => 'Career',
                'code' => 'section-career',
                'image' => 'emo_1637260165.png',
                'description' => 'this section for career',
                'icon' => 'test.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'   => 3,
                'name' => 'Learn',
                'code' => 'section-learn',
                'image' => 'emo_1637260165.png',
                'icon' => 'test.png',
                'description' => 'this section for Learn',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'   => 4,
                'name' => 'Spirit',
                'code' => 'section-spirit',
                'image' => 'emo_1637260165.png',
                'icon' => 'test.png',
                'description' => 'this section for Learn',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'   => 5,
                'name' => 'Health',
                'code' => 'section-health',
                'image' => 'emo_1637260165.png',
                'icon' => 'test.png',
                'description' => 'this section for Health',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'icon' => 'test.png',
                'id'   => 6,
                'name' => 'Emotion',
                'code' => 'section-emotion',
                'image' => 'emo_1637260165.png',
                'description' => 'this section for emotion',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
