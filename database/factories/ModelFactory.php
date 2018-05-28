<?php
/**
 * Created by PhpStorm.
 * User: Dexploitdm
 * Date: 28.05.2018
 * Time: 18:49
 */


use Faker\Generator as Faker;


$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->sentence,
        'description' => $faker->sentence,
        'image' => 'photo1.png',
        'date' => '08/09/18',
        'views' => $faker->numberBetween(0, 5000),
        'category_id' => 1,
        'user_id' => 1,
        'status' => 1,
        'is_featured' => 0
    ];
});


$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'title' => $faker->word, //Одно слово
    ];
});
$factory->define(App\Tag::class, function (Faker $faker) {
    return [
        'title' => $faker->word, //Одно слово
    ];
});

?>