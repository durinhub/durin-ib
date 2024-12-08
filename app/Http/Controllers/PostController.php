<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Purifier;
use Redirect;
use App\Models\Ytanexo;
use App\Models\Anao;
use App\Models\Report;
use Carbon\Carbon;
use Cache;
use Auth;
use App\Helpers\Funcoes;
use App\Enums\ActivityLogClass;
use App\Marmelos\Marmelos;
use App\Enums\AdminRightsEnum;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller {

    public static function pegaPostsCatalogo($secretas = false){
        $chave = 'posts_catalogo' . ($secretas ? '_sec':'');
        if(Cache::has($chave))
            return Cache::get($chave);
            
        $query = Post::with(['arquivos', 'ytanexos'])
            ->orderBy('updated_at', 'desc')
            ->where('lead_id', null)
            ->join('boards','posts.board', '=', 'boards.sigla');

        if(!$secretas)
            $query->where('boards.secreta', false);

        $posts = $query->get();
        Cache::forever($chave, $posts);
        return $posts;
    }

    public static function pegaFiosBoard($siglaBoard){
        $pagina = Controller::getPagina();
        $chave = 'posts_board_' . $siglaBoard . '_pag_' . $pagina;
        if(Cache::has($chave))
            return Cache::get($chave);
            
        $posts = Controller::transformaDatasPortugues(Post::with(['arquivos', 'ytanexos', 'anao', 'ban', 'board'])
        ->orderBy('pinado', 'desc')
        ->orderBy('updated_at', 'desc')
        ->where('board', $siglaBoard)->where('lead_id', null)->paginate(ConfiguracaoController::getAll()->num_posts_paginacao));
        Cache::forever($chave, $posts);
        return $posts;
    }
    
    public static function pegaSubPostsBoard($siglaBoard){
        $pagina = Controller::getPagina();
        $chave = 'subposts_board_' . $siglaBoard . '_pag_' . $pagina;
        if(Cache::has($chave))
            return Cache::get($chave);
            
        $subposts = Post::with(['arquivos', 'ytanexos', 'anao', 'ban', 'board'])
                ->orderBy('created_at', 'asc')
                ->where('board', $siglaBoard)
                ->where('lead_id', '<>', null)->get();
        $subposts = Controller::transformaDatasPortugues($subposts);
        Cache::forever($chave, $subposts);
        return $subposts;
    }
    
    public static function pegaPostsThread($thread){
        $chave = 'posts_thread_' . $thread;
        if(Cache::has($chave))
            return Cache::get($chave);
            
        $posts = Post::with(['arquivos', 'ytanexos', 'anao', 'ban', 'board'])->orderBy('created_at', 'asc')->where('id', $thread)->orWhere('lead_id', $thread)->get();
        $posts = Controller::transformaDatasPortugues($posts);
        Cache::forever($chave, $posts);
        return $posts;
    }
    
    private function pegaPostsBoard($siglaBoard){
        if($this->boardExiste($siglaBoard)){
            $chave = 'posts_board_' . $siglaBoard;
            if(Cache::has($chave))
                return Cache::get($chave);
                
            $posts = Controller::transformaDatasPortugues(Post::with(['arquivos', 'ytanexos', 'ban'])
            ->where('board', $siglaBoard)->get(['id','assunto','modpost','conteudo','sage','pinado','trancado','created_at']));
            if(count($posts) > 0)
                Cache::forever($chave, $posts);
            return $posts;
        } else {
            abort(404);
        }

    }

    public function single($siglaBoard, $postId){
        $postsThread = $this->pegaPostsBoard(strip_tags(Purifier::clean($siglaBoard)));
        $postId = strip_tags(Purifier::clean($postId));
        $post = $postsThread->where('id', '=', $postId)->first();
        if($post){
            return view('partials._fiosubpost')
                ->withViewOnly(true)
                ->withSiglaBoard($siglaBoard)
                ->withPost($post);
        } else {
            abort(404);
        }
    }
    
    public static function pegaReports(){
        $chave = 'reports';
        if(Cache::has($chave))
            return Cache::get($chave);
            
        $reports = Report::orderBy('id', 'desc')->get();
        Cache::forever($chave, $reports);
        return $reports;
    }
    
    private function verificaBoardLegitima($request){
        if(!$this->boardExiste(strip_tags(Purifier::clean($request->siglaboard)))){
            abort(400);
        }
    }
    
    // retorna array com regras de validação
    public function defineArrayValidacao($request){
        $configuracaos = ConfiguracaoController::getAll();
        $regras = array();
        $msgs = array();

        if($request->linkyoutube){
            $regras['linkyoutube'] = 'max:255';
            $msgs['linkyoutube'] = "Máximo de 255 caracteres para links do youtube";
        }
        
        $regras['assunto'] = 'max:255';
        $msgs['assunto'] = "Máximo de 255 caracteres para o assunto";
        
        // caso seja uma nova postagem fora de um fio
        if(strip_tags(Purifier::clean($request->insidepost)) === 'n'){
            $regras['conteudo'] = 'required|max:26300';
            $regras['arquivos.*'] = 'required|mimetypes:image/jpeg,image/png,image/gif,video/webm,video/mp4,audio/mpeg';
            $msgs['arquivos.*.mimetypes'] = "Tipos de arquivos permitidos: jpeg, png, gif, webm, mp4, audio/mpeg";
            $msgs['arquivos.*.required'] = "É necessário pelo menos 1 arquivo";
            $msgs['conteudo.required'] = "É necessário uma mensagem para abrir um fio";
            $msgs['conteudo.max'] = "Tamanho máximo da mensagem: 26300 caracteres";
        }else if( preg_match('/^[0-9]+$/s',strip_tags(Purifier::clean($request->insidepost))) ) { // caso seja dentro de um fio, 
            if($request->conteudo){
                $regras['conteudo'] = 'max:26300';
                $regras['arquivos.*'] = 'mimetypes:image/jpeg,image/png,image/gif,video/webm,video/mp4,audio/mpeg';
                $msgs['arquivos.*.mimetypes'] = "Tipos de arquivos permitidos: jpeg, png, gif, webm, mp4, audio/mpeg";
                $msgs['conteudo.max'] = "Tamanho máximo da mensagem: 26300 caracteres";
            } else {
                $regras['arquivos.*'] = 'required|mimetypes:image/jpeg,image/png,image/gif,video/webm,video/mp4,audio/mpeg';
                $msgs['arquivos.*.mimetypes'] = "Tipos de arquivos permitidos: jpeg, png, gif, webm, mp4, audio/mpeg";
                $msgs['arquivos.*.required'] = "É necessário pelo menos 1 arquivo";
            }
        }
        if($configuracaos->captcha_ativado){
            $regras['captcha'] = 'required|captcha';
            $msgs['captcha'] = "Captcha inválido";
        }
        
        if($request->sage){
            $regras['sage'] = 'max:4';
            $msgs['sage'] = "Input inválido";
        }
        
        if($request->insidepost){
            $regras['insidepost'] = 'max:25';
            $msgs['insidepost'] = "Input inválido";
        }
        
        if($request->lead_id){
            $regras['lead_id'] = 'max:25';
            $msgs['lead_id'] = "Input inválido";
        }
        
        if($request->modpost){
            $regras['modpost'] = 'max:7';
            $msgs['modpost'] = "Input inválido";
        }
        
        if($request->mostra_countryflag){
            $regras['mostra_countryflag'] = 'max:18';
            $msgs['mostra_countryflag'] = "Input inválido";
        }
        
        return array($regras,$msgs);
    }
        
    private function verificaBanimentos($request){
        // Verifica se o postador está banido da board em questão
        $bantime = $this->estaBanido(\Request::ip(), strip_tags(Purifier::clean($request->siglaboard)));
        if($bantime){
            return  'Você está banido da board ' 
                    . strip_tags(Purifier::clean($request->siglaboard)) 
                    . ' até: ' 
                    . $bantime->toDateTimeString() 
                    . ' e não pode postar.'; 
        }
        
        // verifica se o postador esta banido para todas as boards
        $bantime = null;
        $bantime = $this->estaBanido(\Request::ip());
        if($bantime){
            return  'Você está banido de todas as boards até: ' 
                    . $bantime->toDateTimeString() 
                    . ' e não pode postar.';
        }
        return false;
    }
    
    private function validaRequest($request, $arquivos, $links){
        // valida os inputs
        list($regras,$msgs) = $this->defineArrayValidacao($request);
        $this->validate($request, $regras,$msgs);
        
        // validação caso haja link do youtube provido na postagem
        if($links){
            if($request->file('arquivos')){
                return 'Sem anexo de arquivos quando há links de youtube';
            }
            
            foreach($links as $link){
                if(!preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link)){
                    return 'Link inválido';
                } 
            }
        
        } else { // se não houver nenhum link do youtube
            if( (!$request->file('arquivos') && strip_tags(Purifier::clean($request->insidepost)) === 'n') || (is_array($arquivos) && sizeof($arquivos) < 1) ){
                return 'É necessário postar pelo menos com um arquivo ou um link do youtube';
            }
        }
        
        $num_max_arq_post = ConfiguracaoController::getAll()->num_max_arq_post;
        // verifica se há mais arquivos/links que o máximo permitido
        if((is_array($arquivos) && sizeof($arquivos) >  $num_max_arq_post) || ($links && sizeof($links) >  $num_max_arq_post ) ){
            return 'Número máximo de arquivos ou links do youtube permitidos: ' . $num_max_arq_post;
        }

        // verifica se o post está completamente vazio, sem arquivo, sem conteudo
        if(!$request->conteudo && !$links && !$request->file('arquivos')){
            return 'Post vazio';
        }
        
        // termina validação dos inputs
        return false;
    }
    
    private function getLinksYoutube($request){
        if($request->linkyoutube){ // caso haja links do youtube, divida a strings por pipe characters | 
            return explode('|' ,strip_tags(Purifier::clean($request->linkyoutube)));
        }
        return null;
    }
    
    private function setaRosaTextoTags($texto){
        return str_replace("<","&#60;",$texto);
    }


    public function aplicaFiltros(Request $request){
        if(Auth::user()->canDo(AdminRightsEnum::ApplyFiltersPastPosts)){
            try {
                $startId = strip_tags(Purifier::clean($request->startId));
                $endId = strip_tags(Purifier::clean($request->endId));

                $posts = Post::whereBetween("id", [$startId, $endId])->orderBy('updated_at', 'asc')->get();

                \DB::beginTransaction();
                foreach($posts as $post){
                    $post->conteudo = $this->aplicaMarmelos($post->conteudo);
                        $post->save();
                }
                \DB::commit();

                return $this->redirecionaComMsg('sucesso_admin',
                'Filtros aplicados entre os posts de id ' . $startId . ' e ' . $endId,
                $request->headers->get('referer'));
            } catch (Throwable $e) {
                return $this->redirecionaComMsg('erro_admin',
                'Erro ao aplicar filtros nos posts entre ' . $startId . ' e ' . $endId,
                $request->headers->get('referer'));
                \DB::rollback();
            }

        } else abort(404);

    }

    private function aplicaMarmelos($conteudo){
        $ret = $conteudo;
        foreach(Marmelos::$marmelos as $palavra=>$filtros){
            $chave = array_rand($filtros,1); 
            $ret = str_ireplace($palavra,$filtros[$chave],$ret);
        }
        return $ret;
    }

    private function getObjetoPost($request){
        $post = new Post;
        $post->assunto = strip_tags(Purifier::clean($request->assunto)); // assunto do post
        $post->lead_id = (strip_tags(Purifier::clean($request->insidepost)) === 'n' ? null : strip_tags(Purifier::clean($request->insidepost))); // caso o post seja dentro de um fio, define qual fio "pai" da postagem
        $post->board = strip_tags(Purifier::clean($request->siglaboard)); // board que o post pertence
        $request->conteudo = $this->setaRosaTextoTags($request->conteudo);
        $post->conteudo = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $this->aplicaMarmelos(strip_tags(Purifier::clean($request->conteudo))));
        $post->sage = strip_tags(Purifier::clean($request->sage)) === 'sage'; // define se o post foi sageado ou não
        $post->mostra_countryflag = strip_tags(Purifier::clean($request->mostra_countryflag)) === 'mostra_countryflag' || $post->board === 'int'; // define se mostra ou não a countryflag
        $post->pinado = false; // define se a thread está pinada, por padrão, não
        $post->trancado = false; // define se o fio pode receber novos posts ou não
        // flag "modpost" definido pelo mod
        $post->modpost = $request->modpost && Auth::check() && strip_tags(Purifier::clean($request->modpost)) === 'modpost';
        
        return $post;
    }
    
    private function verificaBiscoitoPostar(){
        if(!isset($_COOKIE[$this->nomeBiscoitoSessao]))
            return false;
        
        $biscoito = strip_tags(Purifier::clean($_COOKIE[$this->nomeBiscoitoSessao]));
        $anao = Anao::find($biscoito);
        
        if(!$anao)
            return false;
        
        return $anao;
    }

    private function salvaLinksYoutube($request, $post, $links){
        foreach($links as $link){
            if(preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link, $match)){
                try{
                    $post->ytanexos()->save(new Ytanexo(['ytcode' => $match[1], 'post_id' => $post->id ]));
                } catch(\Illuminate\Database\UniqueConstraintViolationException $e)
                {
                    Funcoes::consolelog("tentou inserir links repetidos no mesmo post, ignorando o link");
                }
                    
            } else 
                throw new \Exception("Link inválido");
            
        }
    }
    
    protected function postAutoIncrementRollback(){
        $maxId = \DB::table('posts')->max('id');
        \DB::statement("ALTER TABLE posts AUTO_INCREMENT=$maxId");
    }

    // verifica se ultrapassou o nro máximo de posts para a board [configuracaos.num_max_fios]
    protected function verificaLimitePosts($siglaBoard){
        $posts = Post::where('pinado', '=', false)
                ->where('board', '=', $siglaBoard)
                ->whereNull('lead_id')
                ->orderBy('updated_at', 'desc')
                ->offset(ConfiguracaoController::getAll()->num_max_fios)
                ->limit(1)
                ->get();
        
        if($posts && count($posts)>0){
            // se houver pelo menos um post retornado desta query
            // significa que a boarda atingiu o nro máximo de fios
            // então deleta o fio mais antigo
            Funcoes::consolelog('PostController::verificaLimitePosts limite atingido, deletando o ultimo fio de ID: ' . $posts[0]->id);
            $this->deletaUmPost($posts[0]);
        }
    }

    // atualiza campo responsável por "bumpar" o fio
    protected function atualizaUpdatedAt($post_id){
        $post = Post::find($post_id);
        if($post){
            $post->updated_at = Carbon::now();
            $post->save();
            
        }
    }
    
    // atualiza variável pinado fazendo que o post fique sempre no topo da primeira página entre outros pinados
    public function pinarPost($siglaBoard, $post_id, $val){
        $siglaBoard = strip_tags(Purifier::clean($siglaBoard));
        $post_id = strip_tags(Purifier::clean($post_id));
        $val = strip_tags(Purifier::clean($val));
        $this->logAuthActivity("Solicitou pinar fio " . $post_id . " board: " . $siglaBoard, ActivityLogClass::Info);
        $board = $this->boardExiste($siglaBoard);
        if(!$board){
            abort(404);
        }

        $post = Post::find($post_id);
        if($post){
            $post->pinado = $val;
            $post->save();
            $this->limpaCachePosts($siglaBoard, $post_id);
            return Redirect::to('/boards/' . $post->board );
        }
        return Redirect::to('/');
    }
    
    // atualiza variável trancado fazendo que o post não possa mais ser respondido
    public function trancarPost($siglaBoard, $post_id, $val){
        $siglaBoard = strip_tags(Purifier::clean($siglaBoard));
        $post_id = strip_tags(Purifier::clean($post_id));
        $val = strip_tags(Purifier::clean($val));
        $this->logAuthActivity("Solicitou trancar fio " . $post_id . " board: " . $siglaBoard, ActivityLogClass::Info);
        $board = $this->boardExiste($siglaBoard);
        if(!$board){
            abort(404);
        }
        
        $post = Post::find($post_id);
        if($post){
            $post->trancado = $val;
            $post->save();
            $this->limpaCachePosts($siglaBoard, $post_id);
            return Redirect::to('/boards/' . $post->board );
        }
        return Redirect::to('/');
    }
    
    // gera um report (denuncia)
    public function report(Request $request){
        if($request && $request->motivo && $request->idpost && $request->siglaboard){
            $this->validate($request, array(
                    'motivo' => 'max:255',
                    'siglaboard' => 'max:10',
                    'idpost' => 'max:4294967294'
                ));
            $report = new Report;

            $report->motivo = strip_tags(Purifier::clean($request->motivo));
            $report->post_id = strip_tags(Purifier::clean($request->idpost));
            $report->board = strip_tags(Purifier::clean($request->siglaboard));

            $report->save();
            Cache::forget('reports');

            return Redirect::to('/boards/' . strip_tags(Purifier::clean($request->siglaboard)));  
        }
        abort(400);
    }
    
    protected function podeDeletarFio($postId){
        $post = Post::find($postId);
        $bisc = $this->temBiscoito();
        if(!$post || !$bisc){
            return false;
        }
        if(Auth::check() || $post->biscoito === $bisc)
            return $post;
        else return false;
    }

    public function deletaPost(Request $request){
        $request->siglaBoard = strip_tags(Purifier::clean($request->siglaBoard));
        $request->postId = strip_tags(Purifier::clean($request->postId));

        $validationRules = [
            'postId' => ['required', 'integer', 'max:999999999999'],
            'siglaBoard' => ['required', 'string', 'max:10'],
        ]; 
        $validationRulesMessages = [
            'postId' => 'Requisição inválida',
            'siglaBoard' => 'Requisição inválida'
        ];

        Validator::make($request->all(), $validationRules, $validationRulesMessages)->validate();

        $this->destroy($request->siglaBoard, $request->postId);

        return $this->redirecionaComMsg('post_deletado', 'Post ' . $request->postId . ' deletado', $request->headers->get('referer'));
    }

    // deleta uma postagem e dados relacionados a ele (links, arquivos)
    public function destroy($siglaBoard, $postId) {
        $board = $this->boardExiste($siglaBoard);
        if(!$board){
            abort(404);
        }

        $post = $this->podeDeletarFio($postId);
        if($post){
            
            if(!$post->lead_id){
                $posts = Post::where('lead_id', $post->id)->get();
                foreach($posts as $p){
                    $this->deletaUmPost($p);
                }
            }
            
            $this->deletaUmPost($post);
            $this->limpaCachePosts($siglaBoard, $post->lead_id);
            return Redirect::to('/boards/' . $siglaBoard );
            
        } else {
            return $this->redirecionaComMsg('ban', 'Não foi possível deletar este post', '/boards/' . $siglaBoard);
        }
    }
    
    private function deletaUmPost($post){
        if($post){
            try{
                \DB::beginTransaction();
                $arquivos = $post->arquivos;
                PostHistoryController::store($post);

                if($post->reports){
                    \DB::table('reports')->where('post_id', '=', $post->id)->delete();
                }
                Cache::forget('reports');

                foreach($arquivos as $arq){
                    (new ArquivoController)->destroyArq($arq->filename);
                    \DB::table('arquivos')->where('post_id', '=', $post->id)->delete();
                }
                if($post->ytanexos){
                    foreach($post->ytanexos as $anexo)
                    {
                        PostHistoryController::storeYtAnexo($anexo->ytcode, $post->id);
                    }
                    \DB::table('ytanexos')->where('post_id', '=', $post->id)->delete();
                }
                $post->delete();
                \DB::commit();
            }
            catch (Throwable $e) {
                \DB::rollback();
                $this->logAuthActivity("Erro ao deletaUmPost: " . $post->id . " msg: " . $e->getMessage(), ActivityLogClass::Erro);

            }

        }
    }
            
    private function deveTrancarFio($postId)
    {
        return Post::where('lead_id', '=', $postId)->count() >= ConfiguracaoController::getAll()->num_max_posts_fio - 1;
        
    }
    
    public function destroyReport($id)
    {
        $id = strip_tags(Purifier::clean($id));
        if(Auth::check())
        {
            $report = Report::find($id);
            if($report){
                $this->logAuthActivity("Deletou report de id " . $id . " e motivo " . $report->motivo . " referente ao post " . $report->post_id . " na board " . $report->board, ActivityLogClass::Info);
                $report->delete();
                Cache::forget('reports');
                return $this->redirecionaComMsg('sucesso_admin', 'Report ' . $id . ' deletado com sucesso', '/admin');
            }
            else
            {
                return $this->redirecionaComMsg('erro_admin', 'Report ' . $id . ' deletado não encontrado', '/admin');
            }
        }
        abort(400);
    }
    
    public function movePost(Request $request)
    {
        if(Auth::check())
        {
            $request->idpost = strip_tags(Purifier::clean($request->idpost));
            $request->novaboard = strip_tags(Purifier::clean($request->novaboard));

            $postMover = Post::find($request->idpost);
            if($postMover){
                $this->logAuthActivity("Moveu postagem " . $request->idpost . " para " . $request->novaboard, ActivityLogClass::Info);
                $velhaBoard = $postMover->board;
                if($postMover->lead_id !== null)
                {
                    return $this->redirecionaComMsg('erro_admin', 'Não foi possível mover esta postagem', '/boards/' . $velhaBoard);
                }
                $postMover->board = $request->novaboard;
                $postMover->save();

                $subposts = Post::where('lead_id', '=', $postMover->id)->get();
                
                foreach($subposts as $post){
                    $post->board = $request->novaboard;
                    foreach($post->reports as $report){
                        $report->board = $request->novaboard;
                        $report->save();
                    } 
                    $post->save();
                }
                foreach($postMover->reports as $report){
                    $report->board = $request->novaboard;
                    $report->save();
                } 
                Cache::forget('reports');
                
                $this->limpaCachePosts($velhaBoard, $postMover->lead_id);
                $this->limpaCachePosts($request->novaboard, $request->idpost);
                return $this->redirecionaComMsg('sucesso_admin', 'Post ' . $request->idpost . ' movido com sucesso para /boards/' . $request->novaboard, '/boards/' . $request->novaboard);
            }
            return $this->redirecionaComMsg('erro_admin', 'Post ' . $request->idpost . ' não encontrado', '/boards/' . $request->novaboard);
        }
        abort(400);
    }

    /**
     * Valida e cria uma nova postagem
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        Funcoes::consolelog('PostController::store');

        if(ConfiguracaoController::getAll()->posts_block)
        {
            return $this->redirecionaComMsg('erro_upload', 
            'Postagens bloqueadas temporariamente. Por favor, tente mais tarde.',
            $request->headers->get('referer'));
        }
        
        // Verifica se a board requisitada para o post realmente existe
        // se não existe, aborta com http 400
        $this->verificaBoardLegitima($request);
        
        //se houver banimentos, retorna
        $msgBan = $this->verificaBanimentos($request);
        if($msgBan){
            return $this->redirecionaComMsg('ban', 
             $msgBan,
            $request->headers->get('referer'));
        }
        $arquivos = $request->file('arquivos'); // salva os dados dos arquivos na variável $arquivos
        $links = $this->getLinksYoutube($request); // pega links do youtube se tiverem
        
        // valida inputs
        $msgValidacao = $this->validaRequest($request, $arquivos, $links);
        if($msgValidacao){
            Funcoes::consolelog('PostController::store erro: ' . $msgValidacao);
            return $this->redirecionaComMsg('erro_upload', 
            $msgValidacao,
            $request->headers->get('referer'));
        }
        
        $post = $this->getObjetoPost($request); // transforma os campos do form da request num objeto Post
        
        // verifica se tem biscoito para postar
        $anao = $this->verificaBiscoitoPostar();
        if($anao){
            $post->biscoito = $anao->biscoito;
        }
        else{
            Funcoes::consolelog('PostController::store erro: tentativa de post sem biscoito');
            return $this->redirecionaComMsg('erro_upload', 
            'Erro ao postar. Você quer biscoito, amigo?',
            $request->headers->get('referer'));
        }
        
        if($post->lead_id){
            $lead_fio = Post::find($post->lead_id);
            if($lead_fio && $lead_fio->trancado){
                return $this->redirecionaComMsg('erro_upload', 
                'Este fio já está trancado',
                $request->headers->get('referer'));
            } elseif($lead_fio 
                    && !$lead_fio->trancado
                    && $this->deveTrancarFio($lead_fio->id)){
                $lead_fio->trancado = true;
                $lead_fio->save();
                
            }
        }

        $arquivosStatus = false; // esta variavel ou é false ou é a lista de arquivos a serem rollbackeados caso necessário fazer um rollback
        $arquivoController = new ArquivoController;

        try{
            // COMEÇA TRANSAÇÃO
            \DB::beginTransaction();

            // salva o post em banco de dados
            $post->save();

            if(!$this->limpaCachePosts($post->board, $post->lead_id)){
                throw new \Exception("Erro ao limpar cache dos posts");
            }

            // caso haja arquivos, salva-os em disco e seus paths em banco
            if (!empty($arquivos)) {
                $arquivosStatus = $arquivoController->salvaArquivosDisco($request, $post, $arquivos);
                if($arquivosStatus === false){
                    throw new \Exception("Erro ao criar arquivos do post");
                }


            } else if($links){ // caso haja links, salva suas referências em banco
                $this->salvaLinksYoutube($request, $post, $links);
            }

            // se for post dentro de fio e não for sage, atualiza sua ultima atualização para que "bumpe"
            if($post->lead_id && !($post->sage)){
                $this->atualizaUpdatedAt($post->lead_id);
            }
            
            // verifica se ultrapassou o limite máximo de fios dentro da board
            if(!$post->lead_id)
                $this->verificaLimitePosts($post->board);
            
            // se chegou até aqui sem nenhuma exception 
            // o fluxo foi de criação de novo post foi bem sucedido então podemos commitar
            \DB::commit();

            // prepara mensagem de aviso de post criado com sucesso
            $flashmsg = $post->lead_id ? 'Post número ' . $post->id . ' criado' : 'Post número <a target="_blank" href="/' . $post->board . '/' . $post->id . '">' . $post->id . '</a> criado';
            return $this->redirecionaComMsg('post_criado', $flashmsg,
            $request->headers->get('referer'));

        } // se qualquer erro ocorreu durante o begin transaction fazemos o rollback no banco de dados e dos arquivos criados
        catch(\Exception $e){
            \DB::rollback();
            $arquivoController->fazRollbackArquivos($arquivosStatus);
            $this->postAutoIncrementRollback();

            $flashmsg = 'Erro ao criar nova postagem';
            return $this->redirecionaComMsg('erro_upload', $flashmsg, $request->headers->get('referer'));

        }
    }    
}
