<?php

use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        'category_id' => 0,
        'title' => $faker->text(50),
        'body' => $faker->text(200),
        'image' => 'default.jpg'
    ];
});

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->text(10)
    ];
});
