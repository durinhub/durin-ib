<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYtanexosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ytanexos', function (Blueprint $table) {
            $table->string('ytcode', 50);
            $table->bigInteger('post_id')->unsigned();
            $table->primary(['ytcode', 'post_id']);
            $table->foreign('post_id')->references('id')->on('posts');   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ytanexos');
    }
}
