<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GutReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gut_reviews')->insert([
            [
                'equipment_id' => 1,
                'match_rate' => 4.5,
                'pysical_durability' => 2.5,
                'performance_durability' => 1.5,
                'review' => 'すごくいいストリングだと思います',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'equipment_id' => 2,
                'match_rate' => 4.5,
                'pysical_durability' => 2.5,
                'performance_durability' => 1.5,
                'review' => 'すごくいいストリングだと思います',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'equipment_id' => 3,
                'match_rate' => 4.5,
                'pysical_durability' => 2.5,
                'performance_durability' => 1.5,
                'review' => 'すごくいいストリングだと思います',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
