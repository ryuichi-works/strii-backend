<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TennisProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tennis_profiles')->insert([
            [
                'user_id' => 1,
                'gender' => '男',
                'my_racket_id' => 1,
                'grip_form' => 'ウエスタン',
                'height' => '普通',
                'age' => '30代前半',
                'physique' => 'がっしり',
                'experience_period' => 10,
                'frequency' => '週１回',
                'play_style' => 'オールラウンダー',
                'favarit_shot' => 'サーブ',
                'weak_shot' => 'バックハンド',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 2,
                'gender' => '女',
                'my_racket_id' => 2,
                'grip_form' => 'フルウエスタン',
                'height' => 'やや小柄',
                'age' => '20代前半',
                'physique' => '普通',
                'experience_period' => 3,
                'frequency' => '月2回',
                'play_style' => 'オールラウンダー',
                'favarit_shot' => 'フォアハンド',
                'weak_shot' => 'バックハンド',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 3,
                'gender' => '男',
                'my_racket_id' => 3,
                'grip_form' => 'フルウエスタン',
                'height' => 'やや小柄',
                'age' => '40代前半',
                'physique' => '普通',
                'experience_period' => 3,
                'frequency' => '月2回',
                'play_style' => 'オールラウンダー',
                'favarit_shot' => 'フォアハンド',
                'weak_shot' => 'バックハンド',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 4,
                'gender' => '男',
                'my_racket_id' => 4,
                'grip_form' => 'フルウエスタン',
                'height' => 'やや小柄',
                'age' => '30代前半',
                'physique' => '普通',
                'experience_period' => 6,
                'frequency' => '月2回',
                'play_style' => 'オールラウンダー',
                'favarit_shot' => 'フォアハンド',
                'weak_shot' => 'バックハンド',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 5,
                'gender' => '女',
                'my_racket_id' => 5,
                'grip_form' => 'フルウエスタン',
                'height' => 'やや小柄',
                'age' => '20代前半',
                'physique' => '普通',
                'experience_period' => 3,
                'frequency' => '月2回',
                'play_style' => 'オールラウンダー',
                'favarit_shot' => 'フォアハンド',
                'weak_shot' => 'バックハンド',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 6,
                'gender' => '男',
                'my_racket_id' => 6,
                'grip_form' => 'フルウエスタン',
                'height' => 'やや小柄',
                'age' => '50代前半',
                'physique' => '普通',
                'experience_period' => 13,
                'frequency' => '月2回',
                'play_style' => 'オールラウンダー',
                'favarit_shot' => 'フォアハンド',
                'weak_shot' => 'バックハンド',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
