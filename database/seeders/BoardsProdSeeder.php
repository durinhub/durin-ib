<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoardsProdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Board::create([
            'sigla' => 'b',
            'nome' => 'Aleatório',
            'descricao' => 'Assuntos aleatórios',
            'ordem' => -1,
            'secreta' => true,
        ]);
        
        \App\Models\Board::create([
            'sigla' => 'a',
            'nome' => 'Aleatório',
            'descricao' => 'Assuntos aleatórios',
            'ordem' => 0,
            'secreta' => false,
        ]);
        
        \App\Models\Board::create([
            'sigla' => 'mod',
            'nome' => 'Moderação',
            'descricao' => 'Contato com a moderação',
            'ordem' => 1,
            'secreta' => false,
        ]);
        
        \App\Models\Board::create([
            'sigla' => 'pol',
            'nome' => 'Notícias e política',
            'descricao' => 'Política, notícias e atualidades',
            'ordem' => 2,
            'secreta' => false,
        ]);
        
        \App\Models\Board::create([
            'sigla' => 'tech',
            'nome' => 'Tecnologia e Computação',
            'descricao' => 'Assuntos de TI, computação, programação, hackearias, empregos na área, Linux, etc',
            'ordem' => 3,
            'secreta' => false,
        ]);
        
        \App\Models\Board::create([
            'sigla' => 'ç',
            'nome' => 'Çandom',
            'descricao' => 'FESTA DURO',
            'ordem' => 32766,
            'secreta' => false,
        ]);
    }
}
