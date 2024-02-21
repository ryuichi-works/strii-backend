<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class GutImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('gut_images')->insert([
        //     [
        //         'file_path' => 'images/guts/default_gut.jpg',
        //         'title' => 'ポリツアープロ',
        //         'maker_id' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'file_path' => 'images/guts/sample_image2.jpg',
        //         'title' => 'ポリツアープロ',
        //         'maker_id' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        // ]);

        DB::table('gut_images')->insert([
            [
                'file_path' => 'images/guts/default_gut_image.jpg',
                'title' => 'デフォルト',
                'maker_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
