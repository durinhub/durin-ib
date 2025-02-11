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
        Schema::table('anaos', function (Blueprint $table) {
            $table->string('ultima_senhadel_usada', 25)->nullable(); // Ultima senha de deleção usada pelo anão, salvo apenas para propósitos de autocomplete
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anaos', function (Blueprint $table) {
            $table->dropColumn('ultima_senhadel_usada');
            //
        });
    }
};
