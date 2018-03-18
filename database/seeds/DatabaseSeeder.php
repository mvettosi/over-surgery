<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        factory(App\Models\User::class, 5)->create();
        factory(App\Models\Appointment::class, 50)->create();
        factory(App\Models\Schedule::class, 50)->create();
        factory(App\Models\Test::class, 50)->create();
        factory(App\Models\Medication::class, 50)->create();
        factory(App\Models\Prescription::class, 50)->create()->each(function ($prescription) {
            $prescription->medications()->sync(
                App\Models\Medication::all()->random(3)
            );
        });
    }
}