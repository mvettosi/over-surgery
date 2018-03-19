<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Prescription::class, function (Faker $faker) {
    return [
        'dose' => $faker->sentence,
        'quantity' => $faker->sentence,
        'patient_id' => function (array $post) {
            return App\Models\User::where('account_type', 'patient')->first()->id;
        },
        'doctor_id' => function (array $post) {
            return App\Models\User::where('account_type', 'doctor')->inRandomOrder()->first()->id;
        },
        'expiration_date' => $faker->dateTimeBetween('-2 days', '+30 days'),
    ];
});
