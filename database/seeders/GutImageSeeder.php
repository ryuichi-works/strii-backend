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
        DB::table('gut_images')->insert([
            [
                'file_path' => 'images/guts/sample_image1.png',
                'title' => 'ポリツアープロ',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'file_path' => 'images/guts/sample_image2.jpg',
                'title' => 'ポリツアープロ',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            // [
            //     'file_name' => 'brast_black.jpg',
            //     'name' => 'ブラストブラック',
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now()
            // ],
        ]);
    }
}
