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
        Schema::create('merchandiser_time_sheets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_user_id');
            $table->foreign('company_user_id')->references('id')->on('company_users')->onDelete('cascade');


            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            
            $table->unsignedBigInteger('store_location_id');
            // $table->foreign('store_location_id')->references('id')->on('stores')->onDelete('cascade');


            $table->string('store_manager_name');

            $table->string('signature')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE merchandiser_time_sheets ENGINE = InnoDB');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchandiser_time_sheets');
        Schema::dropIfExists('time_sheet_records');
    }
};
