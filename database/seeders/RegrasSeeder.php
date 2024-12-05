<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class RegrasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Regra::create([
            'descricao' => 'Não poste conteúdo ilegal'
        ]);
        \App\Models\Regra::create([
            'descricao' => 'Postagens devem seguir o assunto da board'
        ]);
        \App\Models\Regra::create([
            'descricao' => 'Respeite as regras locais de cada board'
        ]);
    }
}
