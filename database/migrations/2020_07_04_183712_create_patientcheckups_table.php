<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientcheckupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patientcheckups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('transaction_id');
            $table->integer('status')->default(0); // 0=>pending, 1=>complete, 2=>incomplete, 3=>not received
            $table->string('code')->unique();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->jsonb('patient_tags')->nullable();
            $table->jsonb('doctor_tags')->nullable();
            $table->jsonb('call_log')->nullable();

            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('doctor_id')->references('id')->on('doctors');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patientcheckups');
    }
}
