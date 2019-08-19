<?php

use App\Entities\User;
use Faker\Generator as Faker;

$factory->define(\App\Entities\Blog\Article::class, function (Faker $faker) {

    return [
        '_id' => $faker->uuid,
        'title' => $faker->title,
        'article_content' => $faker->paragraph(1),
    ];
});
