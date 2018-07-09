<?php

use Faker\Generator as Faker;
use Webpatser\Uuid\Uuid;

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

$factory->define(App\Persistence\UserModel::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        //'first_name' => $faker->firstName,
        //'last_name' => $faker->lastName,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'picture' => $faker->imageUrl
        //'password' => bcrypt('secret')
    ];
});
