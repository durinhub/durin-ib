<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostsPorDia;
//use App\Models\PostsPorDiaBoard;

class StatsController extends Controller
{
    public function postsPorDia(){
        return PostsPorDia::all();         
    }
    // public function postsPorDiaBoard(){
    //     return PostsPorDiaBoard::all();         
    // }
}
