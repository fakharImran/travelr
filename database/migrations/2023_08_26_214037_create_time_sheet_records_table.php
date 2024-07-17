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
        Schema::create('time_sheet_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('time_sheet_id');
            $table->foreign('time_sheet_id')->references('id')->on('merchandiser_time_sheets')->onDelete('cascade');
            
            $table->string('gps_location');

            $table->string('status');
            $table->date('date');
            $table->time('time');

            $table->timestamps();
        });
        DB::statement('ALTER TABLE time_sheet_records ENGINE = InnoDB');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_sheet_records');
    }
};
