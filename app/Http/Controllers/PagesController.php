<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Controllers\BoardController;
use Config;
use Storage;
use Purifier;
use Auth;
use App\Helpers\Funcoes;
use App\Models\Arquivo;
use App\Enums\AdminRightsEnum;
use App\Enums\ActivityLogClass;

class PagesController extends Controller
{
    public function getIndex(){
        Funcoes::consolelog('PagesController::getIndex');
        $this->setaBiscoito();
        
        $regras = RegraController::getIndice();
        $noticias = NoticiaController::getAll();
        return view('pages.indice')
                ->with('regras', $regras)
                ->with('noticias', $noticias)
                ->with('nomeib', ConfiguracaoController::getAll()->nomeib)
                ->withSecretas(Auth::check());
    }
    
    public function getBoard($siglaBoard){
        $this->setaBiscoito();
        $board = $this->boardExiste($siglaBoard);
        if($board){
            $posts = PostController::pegaFiosBoard($siglaBoard);
            $subposts = PostController::pegaSubPostsBoard($siglaBoard);
            
            Funcoes::consolelog('PagesController::getBoard retornando board ' . $siglaBoard);
            return view('pages.board', ['posts' => $posts])
                    ->with('siglaBoard', $siglaBoard)
                    ->with('descrBoard', $board->descricao)
                    ->with('insidePost', 'n')
                    ->with('subPosts', $subposts)
                    ->with('paginador', $posts->appends(\Request::except('page')))
                    ->with('captchaImage', captcha_img())
                    ->with('captchaSize', Config::get('captcha.default.length'))
                    ->with('regras', $board->regras)
                    ->with('ad1', AdsController::getRandom())
                    ->with('ad2', AdsController::getRandom())
                    ->with('requiredConteudo', true)
                    ->withSecretas($board->secreta || Auth::check())
                    ->with('nomeBoard', $board->nome);
            
        } else{
            Funcoes::consolelog('PagesController::getBoard Board não encontrada');
            abort(404);
        }
        
    }
    
    public function getThread($siglaBoard, $thread){
        Funcoes::consolelog('PagesController::getThread');
        $this->setaBiscoito();
        
        $board = $this->boardExiste($siglaBoard);
        if(!$board){
            Funcoes::consolelog('PagesController::getThread Board não encontrada');
            abort(404);
        }

        $thread = strip_tags(Purifier::clean($thread));
        $ver = Post::find($thread);
        if($ver){
            if($ver->lead_id || $ver->board != $siglaBoard){
                Funcoes::consolelog('PagesController::getThread requisição inconsistente');
                abort(404);
            }
        } else {
            Funcoes::consolelog('PagesController::getThread fio não encontrado');
            abort(404);
        }
        
        $posts = PostController::pegaPostsThread($thread);
        
        Funcoes::consolelog('PagesController::getThread retornando fio ' . $thread . ' da board ' . $siglaBoard);
        return view('pages.postshow')
                ->withPosts($posts)
                ->with('siglaBoard', $siglaBoard)
                ->with('descrBoard', BoardController::getAll()->where('sigla', '=', $siglaBoard)->first()->descricao)
                ->with('insidePost', $thread)
                ->with('captchaImage', captcha_img())
                ->with('captchaSize', Config::get('captcha.default.length'))
                ->with('ad1', AdsController::getRandom())
                ->with('ad2', AdsController::getRandom())
                ->withSecretas($board->secreta || Auth::check())
                ->with('nomeBoard', $board->nome);
        
    }
        
    public function getAdmPage($noticiaEditar = null){
        Funcoes::consolelog('PagesController::getAdmPage');
        if(!(Auth::check()) || !$this->temBiscoitoAdmin()){ 
            Funcoes::consolelog('PagesController::getAdmPage erro: não autenticado ou não tem biscoito admin');
            abort(404);
        }
        
        $reports = PostController::pegaReports();

        foreach($reports as $report){
            $post = Post::find($report->post_id);
            $report->lead_id = $post->lead_id;
            
        }
        if($noticiaEditar === null)
            $this->logAuthActivity("Acessou a página de adm", ActivityLogClass::Info);
        $returnedView = view('pages.admin')
            ->withReports($reports)
            ->with('noticiaEditar', $noticiaEditar)
            ->withSecretas(true);
        if(Auth::check() && Auth::user()->canDo(AdminRightsEnum::ChangeRights))
            $returnedView->with('users', UserController::getAll());
        return $returnedView;
    }
    
    public function getCatalogo(Request $request){
        Funcoes::consolelog('PagesController::getCatalogo');
        $this->setaBiscoito();
        $posts = PostController::pegaPostsCatalogo($request->secretas);
        return view('pages.catalogo')->with('nomeib', ConfiguracaoController::getAll()->nomeib)
        ->withPosts($posts)->withSecretas($request->secretas || Auth::check());
    }
    
    public function getLogin(){
        $this->setaBiscoito();
        if($this->temBiscoitoAdmin()){
            Funcoes::consolelog('PagesController::getLogin');
            return view('auth.login')
            ->with('nomeib', ConfiguracaoController::getAll()->nomeib);
        } else {
            Funcoes::consolelog('PagesController::getLogin erro: não tem biscoito admin');
            abort(404);
        }
    }
    
    public function getRegister(){
        $this->setaBiscoito();
        if($this->temBiscoitoAdmin() && Auth::user()->canDo(AdminRightsEnum::RegisterAdmin)){
            Funcoes::consolelog('PagesController::getRegister');
            return view('auth.register')
            ->with('nomeib', ConfiguracaoController::getAll()->nomeib);
        } else {
            Funcoes::consolelog('PagesController::getRegister erro: não tem biscoito admin');
            abort(404);
        }
    }
    
    public function logout(){
        if(Auth::check()){
            $this->logAuthActivity("Fez logout", ActivityLogClass::Info);
            Funcoes::consolelog('PagesController::logout');
            Auth::logout();
            return $this->getIndex();
        } else {
            Funcoes::consolelog('PagesController::logout erro: não está autenticado');
            abort(404);
        }
    }
    
    public function getArquivo($filename){
        Funcoes::consolelog('PagesController::getArquivo ' . $filename);
        $filename = strip_tags(Purifier::clean($filename));
        $arq = Arquivo::where('filename', '=', $filename)->get()->count();
        if(Storage::disk('public')->has($filename) && $arq){
            $fullpath = "app/public/" . $filename;
            return response()->download(storage_path($fullpath), null, [], null);
        } else 
        {
            Funcoes::consolelog('PagesController::getArquivo Arquivo não encontrado');
            abort(404);
        }
    }
}
