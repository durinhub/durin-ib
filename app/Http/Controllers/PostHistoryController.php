<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostHistory;
use App\Models\YtAnexoHistory;
use App\Enums\ActivityLogClass;

class PostHistoryController extends Controller
{
    public static function store(Post $post)
    {
        try{
            $history = new PostHistory;
            $history->id = $post->id;
            $history->assunto = $post->assunto;
            $history->board = $post->board;
            $history->modpost = $post->modpost;
            $history->conteudo = $post->conteudo;
            $history->sage = $post->sage;
            $history->pinado = $post->pinado;
            $history->trancado = $post->trancado;
            $history->biscoito = $post->biscoito;
            $history->lead_id = $post->lead_id;
            $history->post_created_at = $post->created_at;
            $history->post_last_modified = $post->updated_at;
            $history->mostra_countryflag = $post->mostra_countryflag;
            $history->save();
        } catch (\Illuminate\Database\UniqueConstraintViolationException  $e) {
            (new Controller)->logAuthActivity("Erro ao inserir histórico do post " . $post->id, ActivityLogClass::Erro);
            return false;
        }
        return true;
    }
    public static function storeYtAnexo($ytcode, $postId)
    {
        try{
        $history = new YtAnexoHistory;
        $history->ytcode = $ytcode;
        $history->post_id = $postId;
        $history->save();
        } catch (\Illuminate\Database\UniqueConstraintViolationException  $e) {
            (new Controller)->logAuthActivity("Erro ao inserir histórico ytanexo " . $postId . " msg: " . $e->getMessage(), ActivityLogClass::Erro);
            return false;
        }
        return true;
    }
}
