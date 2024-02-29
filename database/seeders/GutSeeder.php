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
                'maker_id' => 2,
                'image_id' => 1,
                'need_posting_image' => true,
                'guage' => '1.15/1.20/1.25/1.30',
                'category' => 'ポリエステル',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name_ja' => 'RPMブラスト',
                'name_en' => 'brust',
                'maker_id' => 5,
                'image_id' => 1,
                'need_posting_image' => true,
                'guage' => '1.20/1.25/1.30/1.35',
                'category' => 'ポリエステル',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name_ja' => 'RPMハリケーン',
                'name_en' => 'rpm hurricane',
                'maker_id' => 5,
                'image_id' => 1,
                'need_posting_image' => true,
                'guage' => '1.20/1.25/1.30',
                'category' => 'ポリエステル',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name_ja' => 'ファントムプロ16',
                'name_en' => 'phantom pro 16',
                'maker_id' => 4,
                'image_id' => 1,
                'need_posting_image' => true,
                'guage' => '1.30',
                'category' => 'ポリエステル',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name_ja' => 'ソニック プロ',
                'name_en' => 'sonic pro',
                'maker_id' => 3,
                'image_id' => 1,
                'need_posting_image' => true,
                'guage' => '1.25/1.30',
                'category' => 'ポリエステル',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name_ja' => 'ポリツアー ファイア',
                'name_en' => 'polytour fire',
                'maker_id' => 2,
                'image_id' => 1,
                'need_posting_image' => true,
                'guage' => '1.20/1.25/1.30',
                'category' => 'ポリエステル',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name_ja' => 'アルパワー',
                'name_en' => 'alu power',
                'maker_id' => 13,
                'image_id' => 1,
                'need_posting_image' => true,
                'guage' => '1.10/1.15/1.20/1.25/1.30',
                'category' => 'ポリエステル',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name_ja' => '4G',
                'name_en' => '4G',
                'maker_id' => 13,
                'image_id' => 1,
                'need_posting_image' => true,
                'guage' => '1.25/1.30',
                'category' => 'ポリエステル',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name_ja' => 'ウィルソン ナチュラルガット16',
                'name_en' => 'wilson natural gut 16',
                'maker_id' => 1,
                'image_id' => 1,
                'need_posting_image' => true,
                'guage' => '1.30',
                'category' => 'ナチュラル',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
