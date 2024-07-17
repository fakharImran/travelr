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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
             
            $table->unsignedBigInteger('store_location_id')->nullable();
            $table->foreign('store_location_id')->references('id')->on('store_locations')->onDelete('cascade');

            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            $table->json('user_ids');
            // $table->foreign('company_user_id')->references('id')->on('company_users')->onDelete('cascade');

            $table->string('title')->nullable();
            $table->string('message')->nullable();
            $table->string('attachment')->nullable();

            
            $table->timestamps();
        });
        DB::statement('ALTER TABLE notifications ENGINE = InnoDB');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
