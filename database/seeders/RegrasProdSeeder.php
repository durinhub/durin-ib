<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegrasProdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
        \App\Models\Regra::create([
            'descricao' => 'Não faça spam ou flood'
        ]);
        \App\Models\Regra::create([
            'descricao' => 'Não poste pornografia',
            'board_name' => 'a'
        ]);
    }
}
