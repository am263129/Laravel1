<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->char('series_id',36);
            $table->string('episode_number',36);
            $table->string('name',50);
            $table->string('overview');
            $table->string('backdrop',80);
            $table->string('season_number');
            $table->boolean('show')->default(0);
            $table->string('cloud',10);

            $table->timestamps();
            $table->foreign('series_id')->references('t_id')->on('series')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('episodes');
    }
}
