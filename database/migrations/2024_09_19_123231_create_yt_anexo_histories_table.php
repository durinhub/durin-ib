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
        Schema::create('ytanexos_histories', function (Blueprint $table) {
            $table->string('ytcode', 50);
            $table->integer('post_id')->unsigned();
            $table->primary(['ytcode', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ytanexos_histories');
    }
};
