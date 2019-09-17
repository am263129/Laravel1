<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_elements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('place')->unique();
            $table->string('root_class')->default('adswrapper');
            $table->string('ins_class')->nullable();
            $table->string('data_ad_client');
            $table->string('data_ad_slot');
            $table->string('data_ad_layout_key')->nullable();
            $table->string('data_ad_test')->nullable();
            $table->string('data_ad_format')->default('auto');
            $table->boolean('active')->default(1);
            $table->boolean('data_ad_full_width_responsive')->default(0);
            $table->boolean('is_non_personalized_ad')->default(0);
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
        Schema::dropIfExists('ad_elements');
    }
}
