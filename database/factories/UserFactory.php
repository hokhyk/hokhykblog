<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Entities\User::class, function (Faker $faker) {
    static $password;

    return [
        '_id' => $faker->uuid,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->unique()->phoneNumber,
//        'password' => Hash::make($faker->password()),
        'password' => $password?:$password = bcrypt('pass'),
    ];
});
