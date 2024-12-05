<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('assunto', 256); 
            $table->string('board', 10); 
            $table->boolean('modpost')->nullable(); 
            $table->text('conteudo');
            $table->boolean('sage');
            $table->boolean('pinado');
            $table->boolean('trancado');
            $table->string('biscoito', 512);
            $table->integer('lead_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('board')->references('sigla')->on('boards');
            $table->foreign('biscoito')->references('biscoito')->on('anaos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
