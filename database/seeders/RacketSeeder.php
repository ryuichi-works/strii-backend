<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RacketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rackets')->insert([
            [
                'posting_user_id' => 1,
                'name_ja' => 'スピードMP',
                'name_en' => 'speed mp',
                'maker_id' => 3,
                'image_id' => 1,
                'need_posting_image' => false,
                'series_id' => 12,
                'head_size' => 100,
                'weight' => 300,
                'balance' => 320,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [   
                'posting_user_id' => 1,
                'name_ja' => 'Vコア 100',
                'name_en' => 'Vcore 100',
                'maker_id' => 2,
                'image_id' => 2,
                'need_posting_image' => false,
                'series_id' => 10,
                'head_size' => 100,
                'weight' => null,
                'balance' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [   
                'posting_user_id' => 2,
                'name_ja' => 'プロスタッフ95',
                'name_en' => 'prostaf95',
                'maker_id' => 2,
                'image_id' => 3,
                'need_posting_image' => false,
                'series_id' => 2,
                'head_size' => 95,
                'weight' => null,
                'balance' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'posting_user_id' => 1,
                'name_ja' => 'ピュアアエロ',
                'name_en' => 'pure aero',
                'maker_id' => 5,
                'image_id' => 4,
                'need_posting_image' => true,
                'series_id' => 24,
                'head_size' => 100,
                'weight' => null,
                'balance' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            
            [   
                'posting_user_id' => 1,
                'name_ja' => 'T-ファイト 100',
                'name_en' => 'T-fight 100',
                'maker_id' => 8,
                'image_id' => 5,
                'need_posting_image' => true,
                'series_id' => 31,
                'head_size' => 100,
                'weight' => null,
                'balance' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [   
                'posting_user_id' => 1,
                'name_ja' => 'ウルトラ100',
                'name_en' => 'ultra100',
                'maker_id' => 1,
                'image_id' => 6,
                'need_posting_image' => false,
                'series_id' => 3,
                'head_size' => 100,
                'weight' => null,
                'balance' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'posting_user_id' => 1,
                'name_ja' => 'Eゾーン',
                'name_en' => 'ezone',
                'maker_id' => 2,
                'image_id' => 7,
                'need_posting_image' => true,
                'series_id' => 7,
                'head_size' => 100,
                'weight' => null,
                'balance' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'posting_user_id' => 1,
                'name_ja' => 'グリンタ',
                'name_en' => 'grinta',
                'maker_id' => 19,
                'image_id' => 8,
                'need_posting_image' => false,
                'series_id' => 42,
                'head_size' => 100,
                'weight' => null,
                'balance' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'posting_user_id' => 1,
                'name_ja' => 'ピュアドライブ',
                'name_en' => 'pure drive',
                'maker_id' => 2,
                'image_id' => 9,
                'need_posting_image' => false,
                'series_id' => 23,
                'head_size' => 100,
                'weight' => null,
                'balance' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
