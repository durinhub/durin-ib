<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Post;
use App\Helpers\Funcoes;
use App\Enums\ActivityLogClass;
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

    private function salvaThumb($post, $filename, $originalFilename, $spoilerVal){
        try{
            $post->arquivos()->save(new Arquivo(
                ['filename' => $filename, 
                'mime' => "image/png", 
                'spoiler' => $spoilerVal ,
                'original_filename' => $originalFilename,
                'thumb' => true
            ]));
            return true;
        } catch(\Exception $e){
            return false;
        }
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

                        $this->salvaThumb($post, $thumbnail, $arq->getClientOriginalName(), $spoilerVal);

                    } else if(!$spoilerVal && ($mime == "image/jpeg" || $mime == "image/png" || $mime == "image/gif")){
                        $thumbnail = $nomeArquivo . ".thumb.jpg";
                        array_push($arquivosRollback, $thumbnail); // salva nomes dos arquivos caso tenha que fazer rollback          

                        $this->generateThumbnail(Storage::disk('public')->path($nomeArquivo), 200, 200, 65);
                        $this->salvaThumb($post, $thumbnail, $arq->getClientOriginalName(), $spoilerVal);

                    }
                        
                    $post->arquivos()->save(new Arquivo(
                    ['filename' => $nomeArquivo, 
                    'mime' => $mime, 
                    'spoiler' => $spoilerVal ,
                    'original_filename' => $arq->getClientOriginalName(),
                    'filesize' => $arq->getSize()
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

    // deleta arquivo da pasta pública e remove sua referência do banco de dados
    public function destroyArqDb($siglaBoard, $filename, $redirect=true){
        $filename = strip_tags(Purifier::clean($filename));
        $siglaBoard = strip_tags(Purifier::clean($siglaBoard));
        $arquivos = Arquivo::where('filename', 'like', '%' . $filename . '%')->get();
        foreach($arquivos as $arq){
            if($arq){
                $thread = Post::where('id', '=', $arq->post_id)->first();
                if($thread){
                    Storage::disk('public')->delete($arq->filename);

                    $arq->delete();
                    $this->limpaCachePosts($siglaBoard, $thread->lead_id === null ? $thread->id : $thread->lead_id );

                } else abort(400);
            } else abort(400);
        }  
        $this->logAuthActivity("Deletou arquivo " . $filename . " da board " . $siglaBoard, ActivityLogClass::Info);  
        return $this->redirecionaComMsg('post_deletado', 'Arquivo ' . $filename . ' deletado', Request()->headers->get('referer'));

    }
}
