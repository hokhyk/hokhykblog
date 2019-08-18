<?php

use Faker\Generator as Faker;

$factory->define(\App\Entities\Blog\Article::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'article_content' => $faker->paragraph(1)
    ];
});
