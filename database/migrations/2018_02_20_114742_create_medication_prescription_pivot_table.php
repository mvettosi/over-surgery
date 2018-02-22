<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMedicationPrescriptionPivotTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('medications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('number');
            $table->timestamps();
        });

        Schema::create('medication_prescription', function (Blueprint $table) {
            $table->integer('medication_id')->unsigned()->index();
            $table->foreign('medication_id')->references('id')->on('medications')->onDelete('cascade');
            $table->integer('prescription_id')->unsigned()->index();
            $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('cascade');
            $table->primary(['medication_id', 'prescription_id']);
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('medication_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('medications');
        Schema::drop('medication_prescription');
    }
}
