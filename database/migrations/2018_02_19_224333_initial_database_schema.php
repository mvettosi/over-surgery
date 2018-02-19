<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitialDatabaseSchema extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique();
            $table->string('surname');
            $table->string('email')->change();
            $table->string('phone');
            $table->string('address');
            $table->string('postcode');
            $table->enum('document_type', array('identity_card', 'passport'));
            $table->string('document_id');
            $table->date('birthdate')->nullable();
            $table->enum('account_type', array('receptionist', 'patient', 'admin', 'doctor', 'nurse'));
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('location');
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('users');
            $table->integer('doctor_or_nurse_id')->unsigned();
            $table->foreign('doctor_or_nurse_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('work_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->time('start_time');
            $table->time('end_time');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('mon');
            $table->boolean('tue');
            $table->boolean('wed');
            $table->boolean('thu');
            $table->boolean('fri');
            $table->boolean('sat');
            $table->boolean('sun');
            $table->integer('worker_id')->unsigned();
            $table->foreign('worker_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('prescriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('medication_name');
            $table->string('dose');
            $table->string('quantity');
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('users');
            $table->integer('doctor_id')->unsigned();
            $table->foreign('doctor_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date_taken');
            $table->string('hospital');
            $table->string('disease');
            $table->string('result');
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('users');
            $table->integer('doctor_id')->unsigned();
            $table->foreign('doctor_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }
}
