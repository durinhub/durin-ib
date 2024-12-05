<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regra;
use Cache;
use Auth;
use Redirect;
use Purifier;
use App\Enums\ActivityLogClass;


class RegraController extends Controller
{
    public static function getIndice(){
        if(Cache::has('regras_indice'))
            return Cache::get('regras_indice');

        $regras = Regra::whereNull('board_name')->orderBy('id')->get();
        
        Cache::forever('regras_indice', $regras);
        return $regras;
    }
    
    public function store(Request $request){
        if(Auth::check()){
            $regra = new Regra;
            
            $regra->descricao = strip_tags(Purifier::clean($request->descricao)); 
            if($request->board_name && $request->board_name !== 'todas')
                $regra->board_name = strip_tags(Purifier::clean($request->board_name));
                
            if( strlen($regra->descricao) > 256 || strlen($regra->board_name) > 10)
                abort(400);
            
            $regra->save();
            $this->logAuthActivity("criou nova regra " . $regra->id . " para board " . $request->board_name, ActivityLogClass::Info);
            if($regra->board_name) Cache::forget('boards');
            else Cache::forget('regras_indice');
            
        }
        return $this->redirecionaComMsg('sucesso_admin', 
        'Nova regra criada com sucesso',
        $request->headers->get('referer'));
    }
    
    public function destroy(Request $request, $id){
        if(Auth::check()){
            $id = strip_tags(Purifier::clean($id)); 
            $regra = Regra::find($id);
            if($regra){
                $this->logAuthActivity("deletou regra " . $regra->id . " para board " . $regra->board_name, ActivityLogClass::Info);
                if($regra->board_name) Cache::forget('boards');
                else Cache::forget('regras_indice');
                $regra->delete();
            }
        }
        //return Redirect('/');
        return $this->redirecionaComMsg('sucesso_admin', 
        'Regra deletada com sucesso',
        $request->headers->get('referer'));
    }
}
