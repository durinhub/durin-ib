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
        Schema::create('dados_mapa_de_posts', function (Blueprint $table) {
            $table->string('ip', 45);
            $table->string('countryregioncode', 4);
            $table->float('latitude');
            $table->float('longitude');
            $table->primary('ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dados_mapa_de_posts');
    }
};
