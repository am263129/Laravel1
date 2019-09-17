<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('report_details',100)->nullable();
            $table->char('report_userid',36)->nullable();
            $table->char('report_movie',36)->nullable();
            $table->char('report_episode',36)->nullable();
            $table->char('report_series',36)->nullable();
            $table->smallInteger('report_from')->nullable();
            $table->smallInteger('report_readit')->default(0);
            $table->smallInteger('report_type');
            $table->timestamps();

           
            $table->foreign('report_userid')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('report_movie')->references('m_id')->on('movies')->onDelete('cascade');
            $table->foreign('report_episode')->references('id')->on('episodes')->onDelete('cascade');
            $table->foreign('report_series')->references('t_id')->on('series')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
