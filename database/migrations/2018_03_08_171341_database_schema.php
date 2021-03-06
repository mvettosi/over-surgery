<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DatabaseSchema extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique()->nullable();
            $table->string('password');
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('postcode')->nullable();
            $table->enum('document_type', array('identity_card', 'passport'))->nullable();
            $table->string('document_id')->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('account_type', array('receptionist', 'patient', 'admin', 'doctor', 'nurse'))->nullable();
            $table->boolean('pwd_updated')->default(false);
            $table->boolean('is_male')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('start_time');
            // $table->dateTime('end_time');
            $table->string('location');
            $table->string('notes')->nullable();
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('users');
            $table->integer('doctor_or_nurse_id')->unsigned();
            $table->foreign('doctor_or_nurse_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->time('start_time');
            $table->tinyInteger('duration')->unsigned();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('mon');
            $table->boolean('tue');
            $table->boolean('wed');
            $table->boolean('thu');
            $table->boolean('fri');
            $table->boolean('sat');
            $table->boolean('sun');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('prescriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('expiration_date');
            $table->boolean('extended')->default(false);
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('users');
            $table->integer('doctor_id')->unsigned();
            $table->foreign('doctor_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('medications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('number');
            $table->timestamps();
        });

        Schema::create('endorsements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dose');
            $table->string('quantity');
            $table->integer('medication_id')->unsigned();
            $table->foreign('medication_id')->references('id')->on('medications');
            $table->integer('prescription_id')->unsigned();
            $table->foreign('prescription_id')->references('id')->on('prescriptions');
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
            $table->boolean('checked')->default(false);
            $table->timestamps();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message');
            $table->integer('sender_id')->unsigned();
            $table->foreign('sender_id')->references('id')->on('users');
            $table->integer('recipient_id')->unsigned()->nullable();
            $table->foreign('recipient_id')->references('id')->on('users');
            $table->enum('sender_type', array('receptionist', 'patient'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('medication_prescription');
        Schema::dropIfExists('medications');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('tests');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
    }
}
