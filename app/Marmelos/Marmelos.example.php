<?php

namespace App\Marmelos;

/*
  Define os marmelos das palavras do imageboard
  no formato "palavra" => array("tradução1", "tradução2",...)
  uma palavra pode ser filtrada para mais de uma outra aleatoriamente
  ou seja, quando o anão escreve "palavra" pode ser traduzido para 
  "tradução1" uma vez mas na próxima como "tradução2" e assim por diante
*/
class MarmelosExemplo{

    static $marmelos = array(

        "palavra1" => array("tradução1","tradução2","tradução3"),
        "palavra2" => array("tradução4","tradução5","tradução6"),
        /*...*/
    );
}