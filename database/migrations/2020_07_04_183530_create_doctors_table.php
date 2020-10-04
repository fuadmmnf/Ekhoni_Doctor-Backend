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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('doctortype_id');
            $table->string('name');
            $table->string('bmdc_number')->unique();
            $table->boolean('payment_style')->default(0); // 0 => paid by customer transactions, 1 => paid by organization
            $table->integer('activation_status')->default(0); //0 pending, 1 activated
            $table->integer('status')->default(0); //0 unavailable, 1 available, 2 in call
            $table->dateTime('active_start');
            $table->integer('is_featured')->default(0); //0 no, 1 yes
            $table->double('pending_amount')->default(0);
            $table->double('commission')->default(0.0);
            $table->double('rate');
            $table->double('offer_rate');
            $table->double('followup_rate')->nullable();
            $table->double('report_followup_rate')->nullable();
            $table->integer('gender'); // 0 => male, 1 => female
            $table->string('email');
            $table->string('workplace')->nullable();
            $table->string('designation')->nullable();
            $table->string('postgrad')->nullable();
            $table->string('medical_college');
            $table->string('other_trainings')->nullable();
            $table->string('portfolio')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->index('user_id', 'status');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('doctortype_id')->references('id')->on('doctortypes');
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
