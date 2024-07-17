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
        Schema::create('company_users', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('access_privilege');
            $table->string('last_login_date_time')->nullable();
            $table->string('date_modified')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE company_users ENGINE = InnoDB');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_users');
        Schema::dropIfExists('merchandiser_time_sheets');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('activities');
    }
};