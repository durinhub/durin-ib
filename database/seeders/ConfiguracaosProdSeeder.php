<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfiguracaosProdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run()
     {
         \App\Models\Configuracao::create([
             'id' => '1',
             'captcha_ativado' => false,
             'carteira_doacao' => 'endereco-carteira-monero',
             'biscoito_admin' => 'cookiesecretodoademir',
             'tempero_biscoito' => 'saltzinho012345',
             //'url_repo' => 'https://gitlab.com',
             'num_max_arq_post' => 5,
             'num_max_fios' => 100,
             'num_posts_paginacao' => 10,
             'num_max_posts_fio' => 500,
             'nomeib' => 'Anões fórum',
             'biscoito_admin_off' => true
         ]);
     }
}
