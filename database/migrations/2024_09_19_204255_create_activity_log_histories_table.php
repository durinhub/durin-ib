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
        Schema::create('activity_log_histories', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->bigInteger('autor_id')->unsigned();
            $table->string('message', 1000);
            $table->integer('class')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->foreign('autor_id')->references('id')->on('users'); 
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_log_histories');
    }
};
