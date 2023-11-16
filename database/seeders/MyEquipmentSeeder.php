<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MyEquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('my_equipments')->insert([
            [
                'user_id' => 1,
                'user_height' => '未設定',
                'user_age' => '未設定',
                'experience_period' => 0,
                'racket_id' => 1,
                'stringing_way' => 'single',
                'main_gut_id' => 1,
                'cross_gut_id' => 1,
                'main_gut_guage' => 1.25,
                'cross_gut_guage' => 1.25,
                'main_gut_tension' => 50,
                'cross_gut_tension' => 50,
                'new_gut_date' => Carbon::now(),
                'change_gut_date' => null,
                'comment' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2,
                'user_height' => '未設定',
                'user_age' => '未設定',
                'experience_period' => 3,
                'racket_id' => 2,
                'stringing_way' => 'hybrid',
                'main_gut_id' => 1,
                'cross_gut_id' => 2,
                'main_gut_guage' => 1.25,
                'cross_gut_guage' => 1.25,
                'main_gut_tension' => 50,
                'cross_gut_tension' => 48,
                'new_gut_date' => Carbon::now(),
                'change_gut_date' => null,
                'comment' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'user_height' => '未設定',
                'user_age' => '未設定',
                'experience_period' => 10,
                'racket_id' => 3,
                'stringing_way' => 'single',
                'main_gut_id' => 2,
                'cross_gut_id' => 2,
                'main_gut_guage' => 1.30,
                'cross_gut_guage' => 1.25,
                'main_gut_tension' => 52,
                'cross_gut_tension' => 50,
                'new_gut_date' => Carbon::now(),
                'change_gut_date' => null,
                'comment' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
