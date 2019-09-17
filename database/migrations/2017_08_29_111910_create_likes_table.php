<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->increments('id');
            $table->char('movie_id',36)->nullable();
            $table->char('series_id',36)->nullable();
            $table->char('uid',36);
            $table->timestamps();

            $table->foreign('movie_id')->references('m_id')->on('movies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('series_id')->references('t_id')->on('series')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('likes');
    }
}
