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
        $appUrl = env("APP_URL");
        
        DB::table('gut_images')->insert([
            [
                'file_path' => "{$appUrl}/storage/images/guts/default_gut_image.png",
                'title' => 'デフォルト',
                'maker_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
