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
        Schema::create('price_audits', function (Blueprint $table) {
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



            $table->string('Product_SKU')->nullable();
            $table->double('product_store_price')->nullable();
            $table->double('tax_in_percentage')->nullable();
            $table->string('competitor_product_name')->nullable();
            $table->double('competitor_product_price')->nullable();
            $table->double('competitor_product_tax')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE price_audits ENGINE = InnoDB');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_audits');
    }
};
