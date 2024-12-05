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
        Schema::create('anao_ips', function (Blueprint $table) {
            $table->string('biscoito', 512);
            $table->string('ip', 45);
            $table->timestamps();
            $table->primary(['biscoito','ip']);
            $table->foreign('biscoito')->references('biscoito')->on('anaos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anao_ips');
    }
};
