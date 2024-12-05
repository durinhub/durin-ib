<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('post_histories', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->string('assunto', 256); 
            $table->string('board', 10); 
            $table->boolean('modpost')->nullable(); 
            $table->text('conteudo');
            $table->boolean('sage');
            $table->boolean('pinado');
            $table->boolean('trancado');
            $table->string('biscoito', 512);
            $table->integer('lead_id')->unsigned()->nullable();
            $table->timestamp('post_created_at')->nullable();
            $table->timestamp('post_last_modified')->nullable();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_histories');
    }
};
