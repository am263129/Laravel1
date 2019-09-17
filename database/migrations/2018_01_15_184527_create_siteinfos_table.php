<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteinfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siteinfos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('social_facebook',150)->nullable();
            $table->string('social_twitter',150)->nullable();
            $table->string('social_instagram',150)->nullable();
            $table->text('privacy')->nullable();
            $table->text('terms')->nullable();
            $table->text('about')->nullable();
            $table->boolean('payment_status')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siteinfos');
    }
}
