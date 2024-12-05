<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguracaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracaos', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('captcha_ativado');
            $table->string('biscoito_admin', 40);
            $table->string('tempero_biscoito', 15);
            $table->string('carteira_doacao', 300)->nullable();
            $table->string('url_repo', 300)->nullable();
            $table->tinyInteger('num_max_arq_post')->nullable();
            $table->tinyInteger('num_max_fios')->nullable();
            $table->tinyInteger('num_posts_paginacao')->nullable();
            $table->smallInteger('num_max_posts_fio')->nullable();
            $table->tinyInteger('num_subposts_post')->default(3);
            $table->boolean('posts_block')->default(false);
            $table->string('nomeib', 40)->default("Imageboard Name");
            $table->boolean('biscoito_admin_off')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracaos');
    }
}
