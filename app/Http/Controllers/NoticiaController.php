<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noticia;
use Cache;
use Auth;
use Redirect;
use Purifier;
use App\Enums\AdminRightsEnum;
use App\Enums\ActivityLogClass;

class NoticiaController extends Controller
{
    public static function getAll(){
        if(Cache::has('noticias'))
            return Cache::get('noticias');

        $noticias = Noticia::orderBy('created_at', 'desc')->get();
        $noticias = Controller::transformaDatasPortugues($noticias);
        Cache::forever('noticias', $noticias);
        return $noticias;
    }
    
    public function store(Request $request){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::NoticiasCrud)){
            $this->logAuthActivity("Criou nova noticia", ActivityLogClass::Info);
            $noticia = new Noticia;
            
            $noticia->assunto = strip_tags(Purifier::clean($request->assunto)); 
            $noticia->conteudo = strip_tags(Purifier::clean($request->conteudo));
            if( strlen($noticia->assunto) > 256 || strlen($noticia->conteudo) > 65535)
                abort(400);

            $noticia->autor_id = Auth::id();
            
            $noticia->save();
            Cache::forget('noticias');
            
        }
        return Redirect('/');
    }
    
    public function destroy($id){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::NoticiasCrud)){
            $id = strip_tags(Purifier::clean($id));
            $noticia = Noticia::find($id);
            if($noticia && $noticia->autor_id === Auth::id() || Auth::user()->canDo()){
                $this->logAuthActivity("Deletou a noticia de id " . $id, ActivityLogClass::Info);
                $noticia->delete();
                Cache::forget('noticias');
            }
        }
        return Redirect('/');
    }
    
    public function edit($id){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::NoticiasCrud)){
            $this->logAuthActivity("Acessou edição de noticia", ActivityLogClass::Info);
            $id = strip_tags(Purifier::clean($id));
            $noticia = Noticia::find($id);
            if($noticia && $noticia->autor_id === Auth::id() || Auth::user()->canDo()){
                return (new PagesController)->getAdmPage($noticia);
            }
        }
        return Redirect('/');
    }
    
    public function update(Request $request){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::NoticiasCrud)){
            $this->logAuthActivity("Editou uma noticia e atualizou o cache", ActivityLogClass::Info);
            $noticia = Noticia::find($request->id);
            if($noticia && $noticia->autor_id === Auth::id() || Auth::user()->canDo()){
                $noticia->id = strip_tags(Purifier::clean($request->id)); 
                $noticia->assunto = strip_tags(Purifier::clean($request->assunto)); 
                $noticia->conteudo = strip_tags(Purifier::clean($request->conteudo));
                if( strlen($noticia->assunto) > 256 || strlen($noticia->conteudo) > 65535)
                    abort(400);
                
                $noticia->update();
                Cache::forget('noticias');
            }
        }
        return Redirect('/');
    }
    
}
