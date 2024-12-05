<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArquivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arquivos', function(Blueprint $table){
            $table->string('filename', 40);
            $table->string('original_filename', 512);
            $table->bigInteger('post_id')->unsigned();
            $table->string('mime', 15);
            $table->boolean('spoiler');
            $table->primary('filename');
            $table->string('filesize', 15)->nullable();
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
        Schema::dropIfExists('arquivos');
    }
}
