<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anaos', function (Blueprint $table) {
            $table->string('biscoito', 512);
            $table->string('user_agent', 1024);
            $table->string('hostname', 1024);
            $table->string('http_acccept_encoding', 1024);
            $table->string('http_acccept_language', 1024);
            $table->char('countrycode', 2)->nullable();
            $table->char('regioncode', 2)->nullable();
            $table->timestamps();
            $table->primary('biscoito');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anaos');
    }
}
