<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostsPorDia;
use App\Models\PostsPorDiaBoard;
use App\Models\DadoMapaDePosts;
use App\Models\Anao;
use Carbon\Carbon;
use Purifier;
use Auth;

class StatsController extends Controller
{
    public function postsPorDia(){
        if(Auth::check()){
            return PostsPorDia::all();
        } else { 
            return PostsPorDia::whereDate('dia', '<', Carbon::today())->get();    
        }       
    }

    public function postsPorDiaBoard(){
        if(Auth::check()){
            return PostsPorDiaBoard::all();
        } else {
            return PostsPorDiaBoard::whereDate('dia', '<', Carbon::today())->get();
        }
    }

    public function salvaDadosMapaDePosts($post){
        $anao = $post->anao;
        $ip = $anao->ipAtual();

        $dado = DadoMapaDePosts::find($ip);
        if(!$dado){
            $dado = new DadoMapaDePosts;
        }

        $dado->ip = $ip;

        $data = $this->getDadosIp($ip);

        if (isset($data['status	']) && $data['status'] === 'fail') {
            return;
        }

        if (isset($data['lat']) && $data['lat']) {
            $dado->latitude = strip_tags(Purifier::clean($data['lat']));
        }
        
        if (isset($data['lon']) && $data['lon']) {
            $dado->longitude = strip_tags(Purifier::clean($data['lon']));
        }
        
        $countryCode = $anao->countrycode;
        $regionCode = $anao->regioncode;
        $dado->countryregioncode = $countryCode . $regionCode;

        if($dado->latitude && $dado->longitude){
            $dado->save();
        }
    }
    public function getDadosMapaDePosts(){
        return DadoMapaDePosts::all(['countryregioncode','latitude','longitude']);

    }
}
