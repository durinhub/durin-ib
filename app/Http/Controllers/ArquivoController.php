<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\HashProibido;
use App\Models\Post;
use App\Helpers\Funcoes;
use App\Enums\ActivityLogClass;
use App\Enums\AdminRightsEnum;
use Auth;
use FFMpeg;
use Imagick;
use Storage;
use Purifier;

use Illuminate\Http\Request;

class ArquivoController extends Controller
{
    
    /**
     * 
     * Generate Thumbnail using Imagick class, fonte: https://stackoverflow.com/a/11376379
     *  
     * @param string $img
     * @param string $width
     * @param string $height
     * @param int $quality
     * @return boolean on true
     * @throws \Exception
     * @throws Imagick\Exception
     */
    private function generateThumbnail($img, $width, $height, $quality = 90)
    {
        if (is_file($img)) {
            $imagick = new \Imagick($img);
            $imagick->setImageFormat('jpeg');
            $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
            $imagick->setImageCompressionQuality($quality);
            $imagick->thumbnailImage($width, $height, false, false);
            if (file_put_contents($img . ".thumb.jpg", $imagick) === false) {
                throw new \Exception("Could not put contents.");
            }
            return true;
        }
        else {
            throw new \Exception("No valid image provided with {$img}.");
        }
    }

    private function salvaThumb($post, $filename, $originalFilename, $spoilerVal, $hash){
        try{
            $post->arquivos()->save(new Arquivo(
                ['filename' => $filename, 
                'mime' => "image/png", 
                'spoiler' => $spoilerVal ,
                'original_filename' => $originalFilename,
                'thumb' => true,
                'sha256' => $hash
            ]));
            return true;
        } catch(\Exception $e){
            return false;
        }
    }

    private function getHashByPath($path){
        $conteudo = Storage::disk('public')->get($path);
        $hash = hash('sha256', $conteudo);
        return $hash;
    }

    private function deletaArquivosPorHash($hash){
        $arquivos = Arquivo::where('sha256', '=', $hash)->get();
        if($arquivos){
            foreach($arquivos as $arq){
                Storage::disk('public')->delete($arq->filename);
                $arq->delete();
            }
        }
    }

    public function atualizaHashArquivosAusentes(){
        if(Auth::user()->canDo(AdminRightsEnum::ApplyFiltersPastPosts)){
            $arquivos = Arquivo::where('sha256', '=', '-')->get();
            $counter = 0;
            $total = count(Arquivo::all());
            if($arquivos){
                foreach($arquivos as $arq){
                    $arq->sha256 = $this->getHashByPath($arq->filename);
                    $arq->save();
                    $counter += 1;
                }
            }
            return $this->redirecionaComMsg('sucesso_admin', "$counter hashes atualizados com sucesso de um total de $total", Request()->headers->get('referer'));

        } else abort(404);

    }

    public function proibeArquivoPorHash($hash){
        try{
            $hashProibido = new HashProibido;
            $hashProibido->sha256 = $hash;
            $hashProibido->save();
        } catch(\Illuminate\Database\UniqueConstraintViolationException $e){
            // ignora erro de hash repetido
        }
    }

    public function verificaHashesProibidos($arquivos, $ip){
        foreach($arquivos as $arq){
            $hashArquivo = hash('sha256', $arq->get());
            $hashDb = HashProibido::find($hashArquivo);

            if($hashDb){
                $controller = new Controller;
                $controller->geraPermaban($ip, "Conteúdo não permitido");

                return true;
            }
            
        }
        return false;
    }

    public function salvaArquivosDisco($request, $post, $arquivos){
        $arquivosRollback = array();
        try{
            foreach ($arquivos as $index => $arq) {
                if ($arq->isValid()) {
                    // define o filename baseado no nro da postagem concatenado com a qtdade de arquivos updados
                    // exemplo, se fio nro 1234 e a postagem tem 3 arquivos, gerará 3 filenames do tipo 1234-0, 1234-1, 1234-2 seguido da extensão do arquivo
                    $contador = 0;
                    do{
                        $nomeArquivo = $post->id . "-{$contador}"  . "." . $arq->extension();
                            
                        $contador++;
                    }while(Storage::disk('public')->exists($nomeArquivo));
                        
                    // salva em disco na pasta public/storage
                    array_push($arquivosRollback, $nomeArquivo); // salva nomes dos arquivos caso tenha que fazer rollback
                    Storage::disk('public')->putFileAs('', $arq, $nomeArquivo);
                    $spoilerVal =  $request->input('arquivos-spoiler-' . ($index+1)) !== null ? $request->input('arquivos-spoiler-' . ($index+1)) === 'spoiler' : false;

                    // gera as thumbnails:
                    $mime = $arq->getMimeType();
                    if(!$spoilerVal && ($mime == "video/webm" || $mime == "video/mp4")){
                        $sec = 0;
                        $thumbnail = $nomeArquivo . '.thumb.png';
                        array_push($arquivosRollback, $thumbnail); // salva nomes dos arquivos caso tenha que fazer rollback
                        
                        $ffmpeg = FFMpeg\FFMpeg::create();
                        $video = $ffmpeg->open(Storage::disk('public')->path($nomeArquivo));
                        $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
                        $frame->save(Storage::disk('public')->path($nomeArquivo) . '.thumb.png');

                        $this->salvaThumb($post, $thumbnail, $arq->getClientOriginalName(), $spoilerVal, $this->getHashByPath($thumbnail));

                    } else if(!$spoilerVal && ($mime == "image/jpeg" || $mime == "image/png" || $mime == "image/gif")){
                        $thumbnail = $nomeArquivo . ".thumb.jpg";
                        array_push($arquivosRollback, $thumbnail); // salva nomes dos arquivos caso tenha que fazer rollback          

                        $this->generateThumbnail(Storage::disk('public')->path($nomeArquivo), 200, 200, 65);
                        $this->salvaThumb($post, $thumbnail, $arq->getClientOriginalName(), $spoilerVal, $this->getHashByPath($thumbnail));

                    }
                    
                    $hashArquivo = hash('sha256', $arq->get());    
                    $post->arquivos()->save(new Arquivo(
                    ['filename' => $nomeArquivo, 
                    'mime' => $mime, 
                    'spoiler' => $spoilerVal ,
                    'original_filename' => $arq->getClientOriginalName(),
                    'filesize' => $arq->getSize(),
                    'sha256' => $hashArquivo
                    ]));
                    Funcoes::consolelog('PostController::salvaArquivosDisco: filename: ' . $nomeArquivo . ' original filename ' . $arq->getClientOriginalName());
                        
                } else {
                    throw new \Exception("Erro: arquivo inválido");
                }
            }
        } catch(\Exception $e){
            $this->fazRollbackArquivos($arquivosRollback);
            return false;
        }
        return $arquivosRollback; // retorna a lista de rollback caso tenha que fazer rollback mais no controlador de post
    }

    // faz rollback dos arquivos na pasta public e no banco de dados
    public function fazRollbackArquivos($arquivos){
        if($arquivos){
            foreach($arquivos as $arq){
                $this->destroyArq($arq);
                Arquivo::where('filename', '=', $arq)->delete();
            }
        }

    }

    // deleta arquivo da pasta pública
    public function destroyArq($filename){
        Storage::disk('public')->delete($filename);
    }

    public function baneEDeletaArquivosPost($post){
        if($post){
            $arquivos = $post->arquivos()->get();
            if($arquivos){
                foreach($arquivos as $arq){
                    $this->deletaArquivosPorHash($arq->sha256);
                    $this->proibeArquivoPorHash(hash: $arq->sha256);
                    $this->limpaCachePosts($post->board, $post->lead_id === null ? $post->id : $post->lead_id );
                }
            }
        }

    }

    // deleta arquivo da pasta pública, remove sua referência do banco de dados e coloca hash do arquivo como proibido
    public function destroyArqDb($siglaBoard, $filename){
        $filename = strip_tags(Purifier::clean($filename));
        $siglaBoard = strip_tags(Purifier::clean($siglaBoard));
        $arquivos = Arquivo::where('filename', 'like', '%' . $filename . '%')->get();
        foreach($arquivos as $arq){
            if($arq){
                $thread = Post::where('id', '=', $arq->post_id)->first();
                if($thread){
                    $this->deletaArquivosPorHash($arq->sha256);
                    $this->proibeArquivoPorHash($arq->sha256);

                    $this->limpaCachePosts($siglaBoard, $thread->lead_id === null ? $thread->id : $thread->lead_id );

                } else abort(400);
            } else abort(400);
        }  
        $this->logAuthActivity("Deletou arquivo " . $filename . " da board " . $siglaBoard, ActivityLogClass::Info);  
        return $this->redirecionaComMsg('post_deletado', 'Arquivo ' . $filename . ' deletado', Request()->headers->get('referer'));

    }
}
