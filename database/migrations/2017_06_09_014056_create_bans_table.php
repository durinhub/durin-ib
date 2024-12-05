<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('bans', function(Blueprint $table){
            $table->string('ip', 15); 
            $table->dateTime('exp_date');  
            $table->string('board', 10)->default('-');
            $table->string('motivo', 255);
            $table->bigInteger('post_id')->unsigned()->nullable();
            $table->primary(['ip', 'exp_date']);
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
        Schema::dropIfExists('bans');
    }
}
