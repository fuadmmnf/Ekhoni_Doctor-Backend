<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorappointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctorappointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patientcheckup_id');
            $table->integer('type')->default(1); // 0 => free, 1 => normal
            $table->string('code')->unique();
            $table->integer('status')->default(0); // 0 => active,  1 => canceled, 2 => finished
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();

            $table->index('patientcheckup_id', 'start_time');
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('patientcheckup_id')->references('id')->on('patientcheckups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctorappointments');
    }
}
