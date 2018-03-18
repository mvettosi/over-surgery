<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Test::class, function (Faker $faker) {
    return [
        'date_taken' => $faker->date,
        'hospital' => $faker->sentence,
        'disease' => $faker->sentence,
        'result' => 'test_result_form.pdf',
        'patient_id' => function (array $post) {
            return App\Models\User::where('account_type', 'patient')->first()->id;
        },
        'doctor_id' => function (array $post) {
            return App\Models\User::where('account_type', 'doctor')->inRandomOrder()->first()->id;
        },
    ];
});
