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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            // $table->String('company');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->String('name_of_store');
            $table->String('parish');
            $table->String('channel')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE stores ENGINE = InnoDB');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
        Schema::dropIfExists('store_locations');
        Schema::dropIfExists('products');
        Schema::dropIfExists('merchandiser_time_sheets');
        Schema::dropIfExists('stock_count_by_stores');
        Schema::dropIfExists('price_audits');
        Schema::dropIfExists('marketing_activities');
        Schema::dropIfExists('out_of_stocks');
        Schema::dropIfExists('opportunities');
        Schema::dropIfExists('product_expiry_trackers');
        Schema::dropIfExists('planogram_compliance_trackers');
    }
};
