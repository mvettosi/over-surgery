<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Endorsement::class, function (Faker $faker) {
    return [
        'dose' => 'Something between this and that',
        'quantity' => '2 packs of this quantity',
        'medication_id' => function (array $post) {
            return App\Models\Medication::inRandomOrder()->first()->id;
        },
        'prescription_id' => function (array $post) {
            return App\Models\Prescription::inRandomOrder()->first()->id;
        },
    ];
});
