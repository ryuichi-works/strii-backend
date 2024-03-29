<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\GutImage;
use App\Models\GutReview;
use App\Models\MyEquipment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            MakerSeeder::class,
            UserSeeder::class,
            RacketImageSeeder::class,
            GutImageSeeder::class,
            GutSeeder::class,
            RacketSeriesSeeder::class,
            RacketSeeder::class,
            AdminSeeder::class,
            TennisProfileSeeder::class,
            MyEquipmentSeeder::class,
            GutReviewSeeder::class,
        ]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
