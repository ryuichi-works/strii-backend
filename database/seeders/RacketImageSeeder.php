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
                'file_path' => 'images/rackets/sample_racket_image1.png',
                'title' => 'プロスタッフ',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'file_path' => 'images/rackets/sample_racket_image2.jpg',
                'title' => 'ピュアアエロ',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
