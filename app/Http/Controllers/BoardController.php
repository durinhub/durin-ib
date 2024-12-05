<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Post;
use App\Models\Regra;
use Illuminate\Http\Request;
use Cache;
use Auth;
use Redirect;
use Purifier;
use Session;
use App\Helpers\Funcoes;
use App\Enums\AdminRightsEnum;
use App\Enums\ActivityLogClass;

class BoardController extends Controller
{
    public static function getAll(){
        if(Cache::has('boards'))
            return Cache::get('boards');
        
        $boards = Board::with(['regras'])->orderBy('ordem')->get();
        
        Cache::forever('boards', $boards);
        return $boards;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        Funcoes::consolelog('BoardController::store');
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::CreateBoards)){
            $board = new Board;
            
            $board->sigla = strip_tags(Purifier::clean($request->sigla));
            $board->nome = strip_tags(Purifier::clean($request->nome));
            $board->descricao = strip_tags(Purifier::clean($request->descricao));
            $board->ordem = strip_tags(Purifier::clean($request->ordem));
            $board->secreta = $request->secreta && Auth::check() && strip_tags(Purifier::clean($request->secreta)) === 'secreta';

            if( strlen($board->nome) > 50 
                    || strlen($board->sigla) > 10
                    || strlen($board->descricao) > 300
                    || $board->ordem > 32767
                    || $board->ordem < -32767
                    || !preg_match("/^[a-zA-Z0-9\-_]+$/", $board->sigla)
                    || !preg_match("/^[a-zA-ZáÁéÉíÍóÓúÚâÂêÊôÔàÀãÃõÕüÜ0çÇ0-9\-_\s]+$/", $board->descricao)
                    || !preg_match("/^[a-zA-ZáÁéÉíÍóÓúÚâÂêÊôÔàÀãÃõÕüÜ0çÇ0-9\-_\s]+$/", $board->nome))
                abort(400);
            
            try{
                Funcoes::consolelog('BoardController::store salvando nova board: ' . $board->sigla);
                $board->save();
                $this->logAuthActivity("Criou nova board " . $board->sigla, ActivityLogClass::Info);
            }
            catch(\Illuminate\Database\QueryException $e){
                Session::flash('erro_admin', 'Erro ao armazenar board: sigla já existente');
                $this->logAuthActivity("Erro de sql ao criar board", ActivityLogClass::Erro);
                return Redirect('/admin');
            }
            catch(Exception $e){
                Session::flash('erro_admin', 'Erro ao armazenar board');
                $this->logAuthActivity("Erro ao criar board", ActivityLogClass::Erro);
                return Redirect('/admin');
            }
            
            Cache::forget('boards');
            return Redirect('/admin');
            
        }
        return Redirect('/');
    }
    
    public function destroy($id){
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::DeleteBoards)){
            $id = strip_tags(Purifier::clean($id));
            $board = Board::where('sigla', '=', $id)->first();
            if($board){
                $this->deletaPostsBoard($board);
                $this->deletaRegrasBoard($board);
                
                Funcoes::consolelog('BoardController::destroy: ' . $board->sigla);
                $this->logAuthActivity("Deletou a board " . $board->sigla, ActivityLogClass::Info);
                $board->delete();
                Cache::forget('boards');
            }
        }
        return Redirect('/');
    }
    
    private function deletaPostsBoard($board){
        $posts = Post::where('board', '=', $board->sigla)->whereNull('lead_id')->get();
        $postController = new PostController();
        
        if($posts && count($posts) > 0){
            foreach($posts as $post){
                $postController->destroy($board->sigla, $post->id);
                
            }
        }
    }
    
    private function deletaRegrasBoard($board){
        $regras = Regra::where('board_name', '=', $board->sigla)->get();
        $regraController = new RegraController();
        
        if($regras && count($regras) > 0){
            foreach($regras as $regra){
                $regraController->destroy($regra->id);
            }
        }
    }
}
