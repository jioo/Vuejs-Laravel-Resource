<?php

use Faker\Generator as Faker;

$factory->define(App\Movie::class, function (Faker $faker) {
    return [
        'category_id' => 0,
        'title' => $faker->text(20),
        'year' => '2018',
        'url' => 'movie.mkv',
        'youtubeId' => '',
        'image' => 'default.jpg'
    ];
});

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->text(10)
    ];
});
