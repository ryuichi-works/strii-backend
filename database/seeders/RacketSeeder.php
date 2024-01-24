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
                'name_ja' => 'ピュアアエロ',
                'name_en' => 'pure aero',
                'maker_id' => 2,
                'image_id' => 2,
                'need_posting_image' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name_ja' => 'Vコア 100',
                'name_en' => 'Vcore 100',
                'maker_id' => 1,
                'image_id' => null,
                'need_posting_image' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name_ja' => 'プロスタッフ95',
                'name_en' => 'prostaf95',
                'maker_id' => 3,
                'image_id' => 1,
                'need_posting_image' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
