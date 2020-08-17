<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckupprescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkupprescriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patientcheckup_id');
            $table->integer('status'); // 0 => not created, 1 => submitted
            $table->string('code');
            $table->json('contents')->nullable();
            $table->string('prescription_path')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('checkupprescriptions');
    }
}
