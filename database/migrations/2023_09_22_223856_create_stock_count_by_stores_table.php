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
        Schema::create('stock_count_by_stores', function (Blueprint $table) {
            $table->id();
           
          
            $table->unsignedBigInteger('store_location_id');
            $table->foreign('store_location_id')->references('id')->on('store_locations')->onDelete('cascade');

            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            $table->unsignedBigInteger('company_user_id')->nullable();
            $table->foreign('company_user_id')->references('id')->on('company_users')->onDelete('cascade');

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->string('product_sku');
            $table->bigInteger('stock_on_shelf');
            $table->string('stock_on_shelf_unit');

            $table->bigInteger('stock_packed');
            $table->string('stock_packed_unit');

            $table->bigInteger('stock_in_store_room');
            $table->string('stock_in_store_room_unit');

            $table->timestamps();
        });
        DB::statement('ALTER TABLE stock_count_by_stores ENGINE = InnoDB');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_count_by_stores');
    }
};
