<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Appointment::class, function (Faker $faker) {
    $startTime = $faker->dateTimeBetween('-2 days', '+30 days');
    $endTime = clone $startTime;
    $endTime->modify('+ 1 hour');
    return [
        'start_time' => $startTime,
        'end_time' => $endTime,
        'location' => $faker->address,
        'notes' => $faker->text(99),
        'patient_id' => function (array $post) {
            return App\Models\User::where('account_type', 'patient')->first()->id;
        },
        'doctor_or_nurse_id' => function (array $post) {
            return App\Models\User::where('account_type', 'doctor')->orWhere('account_type', 'nurse')->inRandomOrder()->first()->id;
        },
    ];
});
