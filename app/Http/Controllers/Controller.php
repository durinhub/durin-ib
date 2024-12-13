<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\Ban;
use App\Models\Post;
use Purifier;
use Carbon\Carbon;
use Cache;
use Redirect;
use Session;
use Auth;
use App\Helpers\Funcoes;
use App\Enums\AdminRightsEnum;
use App\Enums\ActivityLogClass;

class Controller extends BaseController {
    use AuthorizesRequests, ValidatesRequests;

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;
    var $nomeBiscoitoSessao = "biscoito";

    protected function iniciaLog($nome) {
        return fopen($nome . "--" . date("Y-m-d") . ".tlog", "a+");
    }

    protected function escreveLog($tag, $msg, $arq) {
        fwrite($arq, "tag=" . $tag . "-" . "data=" . date('Y/m/d-h:m:s-') . "LOG-MSG=" . $msg . "-|-\n");
    }

    protected function terminaLog($logArq) {
        fclose($logArq);
    }
    
    protected function redirecionaComMsg($tagMsg, $msg, $enderecoRed='/')
    {
        Session::flash($tagMsg, $msg);
        return Redirect::to($enderecoRed);
    }
    
    protected function limpaCachePosts($board, $thread){
        try{
            $num_paginas = ConfiguracaoController::getAll()->num_posts_paginacao;
            for($i = 0 ; $i < $num_paginas ; $i++ ){
                Cache::forget('posts_board_' . $board);
                Cache::forget('posts_board_' . $board . '_pag_' . $i);
                Cache::forget('subposts_board_' . $board  . '_pag_' . $i);
            }
            Cache::forget('posts_thread_' . $thread);
            Cache::forget('posts_catalogo');
            Cache::forget('posts_catalogo_sec');
            
            return true;
        } catch(\Exception $e){
            return false;
        }
    }

    public function geraPermaban($ip, $motivo){
        try{
            \DB::beginTransaction();
            $ban = new Ban;

            $ban->ip = $ip;        
            $ban->exp_date = Carbon::now()->addYears(100);
            $ban->motivo = $motivo;

            $ban->save(); 
            \DB::commit();
        } catch(\Exception $e){
            \DB::rollback();
        }
    }
    
    protected function getDadosIp($ip){
        $url = "http://ip-api.com/json/{$ip}";
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $response = curl_exec($ch);
    
        curl_close($ch);
    
        return json_decode($response, true);
    }

    public function banirUsuarioRequest(Request $request){
        
        $ban = new Ban;
        $ban->ip = \App\Models\Post::find(strip_tags(Purifier::clean($request->idpost)))->anao->ip();
        $hours = (float) strip_tags(Purifier::clean($request->nro_horas));
        $days = (float) strip_tags(Purifier::clean($request->nro_dias));
        $ehPermaban = strip_tags(Purifier::clean($request->permaban)) === 'permaban';
        $banfiles = strip_tags(Purifier::clean($request->banfiles)) === 'banfiles';
        
        if($hours == 0 && $days == 0 && !$ehPermaban){
            return $this->redirecionaComMsg('ban', 'Erro ao banir usuário: deve-se colocar um tempo de banimento ou permaban', $request->headers->get('referer'));
        }

        $ban->exp_date =  $ehPermaban ?  Carbon::now()->addYears(100) : Carbon::now()->addHours($hours)->addDays($days);
        $post = Post::find(strip_tags(Purifier::clean($request->idpost)));
        if(!$post)
            return $this->redirecionaComMsg('ban', 'Erro ao banir usuário: post inexistente', $request->headers->get('referer'));
        $ban->post_id = $post->id;
        
        if( strip_tags(Purifier::clean($request->board)) !== 'todas'){
            $ban->board = strip_tags(Purifier::clean($request->board));
        }
        
        $ban->motivo = strip_tags(Purifier::clean($request->motivo));
        
        $ban->save();

        if($banfiles){
            (new ArquivoController)->baneEDeletaArquivosPost($post);
        }

        $this->logAuthActivity("Baniu usuário " . $ban->ip . " por post " . $ban->post_id, ActivityLogClass::Info);
        $this->limpaCachePosts($post->board, $post->lead_id === null ? $post->id : $post->lead_id );
        
        return $this->redirecionaComMsg('ban', "Banido anão que fez o post $post->id", $request->headers->get('referer'));

    }
    
    public function estaBanido($ip, $siglaBoard=null){
        if($siglaBoard===null){
            $bans = \DB::table('bans')->where('ip', '=', $ip)->where('board', '=', '-')->orderBy('exp_date', 'desc')->get();
        } else {
            $bans = \DB::table('bans')->where('ip', '=', $ip)->where('board', '=', $siglaBoard)->orderBy('exp_date', 'desc')->get();
        }
        if(count($bans)>0) {
            $banTime = Carbon::parse($bans[0]->exp_date);
            
            if( Carbon::now()->gt($banTime) ){
                return false;
            } else {
                return $banTime;
            }
            
        } else {
            return false;
        }
    }
    
    public function temBiscoito(){
        if(isset($_COOKIE[$this->nomeBiscoitoSessao]))
            return strip_tags(Purifier::clean($_COOKIE[$this->nomeBiscoitoSessao]));
        else return false;
    }
    
    private function geraBiscoito()
    {
        $request = Request();
        $userAgent = strip_tags(Purifier::clean($request->server('HTTP_USER_AGENT')));
        $remoteAddr = strip_tags(Purifier::clean($request->server('REMOTE_ADDR')));
        $hostname = gethostbyaddr($remoteAddr);
        $accept_encoding = strip_tags(Purifier::clean($request->server('HTTP_ACCEPT_ENCODING')));
        $accept_lang = strip_tags(Purifier::clean($request->server('HTTP_ACCEPT_LANGUAGE')));
        
        Funcoes::consolelog("Controller::geraBiscoito gerando biscoito com dados\n User Agent: "
        . $userAgent
        . "\nIp: " . $remoteAddr
        . "\nAccept encoding: " . $accept_encoding
        . "\nAccept language: " . $accept_lang
        . "\nHost by addr: " . $hostname);

        $stringGerarBiscoito = $userAgent
        . $remoteAddr 
        . $hostname
        . ConfiguracaoController::getAll()->tempero_biscoito;

        return hash("sha512", $stringGerarBiscoito);

    }

    protected function setaBiscoito(){
        $anaoController = new AnaoController;
        $valorBiscoito = $this->geraBiscoito();
        $biscoitoBrowser = $this->temBiscoito();

        if(!($biscoitoBrowser) || 
            ($biscoitoBrowser && !$anaoController->existeAnaoDb($valorBiscoito)) || 
            $biscoitoBrowser !== $valorBiscoito){
            $anaoController->salvaAnao($valorBiscoito);
            
            header("Set-Cookie: $this->nomeBiscoitoSessao=$valorBiscoito; httpOnly; path=/");
        }
    }
    
    protected function temBiscoitoAdmin(){
        return (ConfiguracaoController::getAll()->biscoito_admin_off) || (Auth::check() && Auth::user()->canDo(AdminRightsEnum::BypassAdmCookie)) || (isset($_COOKIE['biscoitoAdmin']) && 
                $_COOKIE['biscoitoAdmin'] === ConfiguracaoController::getAll()->biscoito_admin);
    }
    
    public static function getPagina(){
        if(isset($_GET['page'])){
            if(strlen($_GET['page']) > 3) return 1;
            return intval($_GET['page']);
        }
        else{
            return 1;
        }
    }
    
    public static function pegaMesPortugues($numMes){
        switch($numMes){
            case 1:
                return 'Janeiro';
            case 2:
                return 'Fevereiro';
            case 3:
                return 'Março';
            case 4:
                return 'Abril';
            case 5:
                return 'Maio';
            case 6:
                return 'Junho';
            case 7:
                return 'Julho';
            case 8:
                return 'Agosto';
            case 9:
                return 'Setembro';
            case 10:
                return 'Outubro';
            case 11:
                return 'Novembro';
            case 12:
                return 'Dezembro';
            default:
                return '';
        }
    }
    
    public static function transformaDatasPortugues($posts){
        foreach($posts as $post){
            $temp = strlen($post->created_at->day) === 1 ? '0' . $post->created_at->day : $post->created_at->day ;
            $temp .= ' de ';
            $temp .= PostController::pegaMesPortugues($post->created_at->month);
            $temp .= ' de ';
            $temp .= $post->created_at->year;
            $temp .= ' às ';
            $temp .= $post->created_at->hour;
            $temp .= ':';
            $temp .= strlen($post->created_at->minute) === 1 ? '0' . $post->created_at->minute : $post->created_at->minute ;
            
            $post->data_post = $temp;
        }
        return $posts;
    }
    
    public function boardExiste($siglaBoard){
        $boards = BoardController::getAll();
        foreach($boards as $board){
            if($board->sigla === $siglaBoard)
                return $board;
        }
        return false;
    }
    
    protected function logAuthActivity($message, $class)
    {
        ActivityLogController::store(Auth::id(), $message, $class);
    }
    
    protected function purifyAllParams(Request $request){

        $params = $request->all();
        foreach($params as $key => $param){
            if($param)
                $request[$key] = strip_tags(Purifier::clean($param));
        }
        return $request;
    }

}
