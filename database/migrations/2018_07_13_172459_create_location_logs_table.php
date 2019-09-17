<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status', 8);
            $table->string('country_code',5);
            $table->string('country', 20);
            $table->string('city', 25);
            $table->string('ip', 16);
            $table->string('zip_code', 10);
            $table->string('isp', 20);
            $table->string('user_agent', 120)->nullable();
            $table->string('confirm_hash', 60)->nullable();
            $table->char('uid', 36);
            $table->timestamps();

            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_logs');
    }
}
