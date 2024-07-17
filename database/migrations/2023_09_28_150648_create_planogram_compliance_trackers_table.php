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
        Schema::create('planogram_compliance_trackers', function (Blueprint $table) {
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


            $table->string('product_number_sku')->nullable();
            $table->string('is_planogram_compliance')->nullable();
            $table->string('photo_before_stocking_shelf')->nullable();
            $table->string('photo_after_stocking_shelf')->nullable();
            $table->string('action')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE planogram_compliance_trackers ENGINE = InnoDB');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('planogram_compliance_trackers');
    }
};
