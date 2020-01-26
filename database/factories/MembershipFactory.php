<?php

use Faker\Generator as Faker;

$factory->define(App\Persistence\MembershipModel::class, function (Faker $faker) {
    $startsAt = $faker->dateTimeBetween('-1 years', 'now');
    return [
        'created_at' => $faker->dateTimeBetween('-1 years', '-1 month'),
        'starts_at' => $faker->dateTimeBetween('-1 years', '-1 month'),
        'expires_at' => $faker->dateTimeBetween('+1 month', '+1 years'),
        'payment_method' => 'test_payment'
    ];
});

$factory->state(App\Persistence\MembershipModel::class, 'unpaid', [
    'paid_at' => null
]);

$factory->state(App\Persistence\MembershipModel::class, 'paid', function(Faker $faker) {
    return [
        'paid_at' => $faker->dateTimeBetween('-1 years', 'now')
    ];
});