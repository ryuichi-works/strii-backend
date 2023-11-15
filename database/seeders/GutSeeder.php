<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('guts')->insert([
            [
                'name_ja' => 'ポリツアープロ',
                'name_en' => 'poly tour pro',
                'maker_id' => 1,
                'image_id' => 2,
                'need_posting_image' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name_ja' => 'ブラスト',
                'name_en' => 'brust',
                'maker_id' => 2,
                'image_id' => 1,
                'need_posting_image' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name_ja' => 'プロハリケーンツアー',
                'name_en' => '',
                'maker_id' => 2,
                'image_id' => null,
                'need_posting_image' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
