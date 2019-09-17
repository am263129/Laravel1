<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateRecenltyWatchedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recently_watcheds', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->char('episode_id', 36)->nullable();
            $table->char('series_id',36)->nullable();
            $table->char('movie_id',36)->nullable();
            $table->integer('current_time');
            $table->integer('duration_time');
            $table->char('uid',36);
            $table->timestamps();
            
            $table->foreign('movie_id')->references('m_id')->on('movies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('episode_id')->references('id')->on('episodes')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('recently_watcheds');
    }
}
