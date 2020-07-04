<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('bmdc_number');
            $table->integer('activation_status')->default(0); //0 pending, 1 activated
            $table->integer('status')->default(0); //0 available, 1 busy, 2 in call
            $table->double('rate');
            $table->double('offer_rate');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('max_appointments_per_day')->nullable();
            $table->integer('gender'); // 0 => male, 1 => female
            $table->string('mobile');
            $table->string('email');
            $table->string('workplace')->nullable();
            $table->string('designation')->nullable();
            $table->string('postgrad')->nullable();
            $table->string('medical_college')->nullable();
            $table->string('others_training')->nullable();

            $table->string('device_ids')->nullable(true);
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctors');
    }
}
