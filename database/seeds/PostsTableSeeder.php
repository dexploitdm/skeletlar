<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Post::class, function (Faker\Generator $faker) {
            return [
                'title' => $faker->sentence,
                'content' => $faker->sentence,
                'image' => 'photo1.png',
                'date' => '08/09/18',
                'views' => $faker->numberBetween(0, 5000),
                'category_id' => 1,
                'user_id' => 1,
                'status' => 1,
                'is_featured' => 0
            ];
        });

    }
}
