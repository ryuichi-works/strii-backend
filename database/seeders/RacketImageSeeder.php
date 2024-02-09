<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RacketImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('racket_images')->insert([
            [
                'posting_user_id' => 1,
                'file_path' => 'images/rackets/default_racket_image.png',
                'title' => 'デフォルトラケットイメージ',
                'maker_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'posting_user_id' => 2,
                'file_path' => 'images/rackets/default_racket_image1.jpg',
                'title' => 'ピュアアエロ',
                'maker_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'posting_user_id' => 3,
                'file_path' => 'images/rackets/default_racket_image2.jpg',
                'title' => 'ブレード',
                'maker_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'posting_user_id' => 3,
                'file_path' => 'images/rackets/default_racket_image3.jpg',
                'title' => 'プロスタッフ',
                'maker_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'posting_user_id' => 1,
                'file_path' => 'images/rackets/default_racket_image4.jpg',
                'title' => 'v コア100',
                'maker_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'posting_user_id' => 1,
                'file_path' => 'images/rackets/default_racket_image5.jpg',
                'title' => 'スピード',
                'maker_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'posting_user_id' => 2,
                'file_path' => 'images/rackets/default_racket_image6.jpg',
                'title' => 'o3',
                'maker_id' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'posting_user_id' => 3,
                'file_path' => 'images/rackets/default_racket_image7.jpg',
                'title' => 'ピュアドライブ',
                'maker_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
