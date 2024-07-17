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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('store_location_id');
            // $table->foreign('store_location_id')->references('id')->on('store_locations')->onDelete('cascade');

            // $table->unsignedBigInteger('store_id')->nullable();
            // $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            // $table->unsignedBigInteger('category_id')->nullable();
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            // $table->unsignedBigInteger('product_id')->nullable();
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->unsignedBigInteger('company_user_id');
            $table->foreign('company_user_id')->references('id')->on('company_users')->onDelete('cascade');

            
            $table->string('activity_description')->nullable();
            $table->string('activity_type')->nullable();
            $table->json('activity_detail')->nullable();
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
        Schema::dropIfExists('activities');
    }
};
