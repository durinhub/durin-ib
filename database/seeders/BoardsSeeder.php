<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class BoardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Board::create([
            'sigla' => 'b',
            'nome' => 'Aleatório',
            'descricao' => 'Assuntos aleatórios (o bê raiz)',
            'ordem' => -1,
            'secreta' => true,
        ]);
        
        \App\Models\Board::create([
            'sigla' => 'mod',
            'nome' => 'Moderação',
            'descricao' => 'Board destinada a realização de contato com a moderação',
            'ordem' => 1,
            'secreta' => false,
        ]);
        
        \App\Models\Board::create([
            'sigla' => 'pol',
            'nome' => 'Notícias e política',
            'descricao' => 'Política, notíciais (atuais ou não) e suas implicações em qualquer área',
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
            'sigla' => 'w',
            'nome' => 'Wallpapers',
            'descricao' => 'Wallpapers para computadores e celulares.',
            'ordem' => 4,
            'secreta' => false,
        ]);
        
        \App\Models\Board::create([
            'sigla' => 'x',
            'nome' => 'Paranormal, Religiões, Teologia',
            'descricao' => 'Religiões, eventos paranormais, teologia, ocultismo, gnose, etc.',
            'ordem' => 5,
            'secreta' => false,
        ]);
        
        \App\Models\Board::create([
            'sigla' => 'jo',
            'nome' => 'Jogos',
            'descricao' => 'Jogos para qualquer plataforma.',
            'ordem' => 6,
            'secreta' => false,
        ]);
        
        \App\Models\Board::create([
            'sigla' => 'cu',
            'nome' => 'Cultura, música, televisão, literatura',
            'descricao' => 'Assuntos culturais de cunho artístico',
            'ordem' => 7,
            'secreta' => false,
        ]);
        
        \App\Models\Board::create([
            'sigla' => 'an',
            'nome' => 'Anime',
            'descricao' => 'Desenhos chineses inventados por Mitsubishi Toyota',
            'ordem' => 8,
            'secreta' => false,
        ]);
        
        \App\Models\Board::create([
            'sigla' => 'ç',
            'nome' => 'Çandom',
            'descricao' => 'FESTA DURO',
            'ordem' => 9,
            'secreta' => false,
        ]);
        
        \App\Models\Board::create([
            'sigla' => 'a',
            'nome' => 'Aleatório',
            'descricao' => 'Assuntos aleatórios',
            'ordem' => 0,
            'secreta' => false,
        ]);
    }
}
