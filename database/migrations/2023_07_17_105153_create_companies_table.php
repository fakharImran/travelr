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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->String('company');
            $table->String('code');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE companies ENGINE = InnoDB');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
        Schema::dropIfExists('stores');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('company_users');
    }
};
