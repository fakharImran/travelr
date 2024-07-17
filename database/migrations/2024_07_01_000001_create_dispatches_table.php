<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispatches', function (Blueprint $table) {

            $table->id();
            $table->string('pick_up_address')->nullable();
            $table->string('drop_off_address')->nullable();
            $table->string('phone_no')->nullable();
            $table->bigInteger('fare')->nullable();
            $table->string('send_button')->nullable();
            $table->string('time_away')->nullable();
            $table->string('status')->nullable();

            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');


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
        Schema::dropIfExists('dispatches');
    }
};
