<?php

use Faker\Generator as Faker;

$factory->define(App\Persistence\CourseModel::class, function (Faker $faker) {
    $startsAt = $faker->dateTimeBetween('-1 years', '+1 years');
    $weeks = $faker->numberBetween(1, 10);
    $endsAt = clone $startsAt;
    $endsAt->add(new DateInterval("P".$weeks."W"));
    return [
    	'id' => $faker->uuid(),
        'name' => $faker->catchPhrase,
        'weeks' => $weeks,
        'starts_at' => $startsAt,
        'ends_at' => $endsAt,
        'duration_minutes' => $faker->numberBetween(1,24)*5,
        'description' => $faker->sentence(10)
    ];
});
