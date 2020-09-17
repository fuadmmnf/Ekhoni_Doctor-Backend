<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('mobile')->unique();
            $table->string('code')->unique();
            $table->integer('status')->default(0); // 0 => not in call; 1 => in call
            $table->boolean('is_agent')->default(false);
            $table->double('agent_percentage')->default(0.0);
            $table->double('balance')->default(0.0);
            $table->double('pending_amount')->default(0.0);
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
        Schema::dropIfExists('users');
    }
}
