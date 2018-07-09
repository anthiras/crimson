<?php

use Faker\Generator as Faker;

$factory->define(App\Persistence\CourseModel::class, function (Faker $faker) {
    return [
    	'id' => $faker->uuid(),
        'name' => $faker->catchPhrase,
        'weeks' => $faker->numberBetween(1, 10),
        'starts_at' => $faker->dateTime,
        'ends_at' => $faker->dateTime,
        'duration_minutes' => $faker->numberBetween(1,24)*5
    ];
});
