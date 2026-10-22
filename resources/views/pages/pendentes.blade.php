@extends('boardbase')


@section('titulo', 'Posts pendentes de aprovação')

@section('conteudo')

<div class="row">
<div class="col-sm-1"></div>
<div class="col-sm-10">
    <h1 class="text-center">Posts pendentes de aprovação</h1>
    <h2 class="text-center">Mais recente primeiro</h2>
</div>
<div class="col-sm-1"></div>
</div>
<br>
<div class="row">
    <div class="col-sm-12">
        <table>
            <tr>
                <th>Board</th>
                <th>Nro</th>
                <th>Assunto</th>
                <th>Conteúdo</th>
                <th>Mídia</th>
                <th>Data</th>
                <th>Aprovação</th>
            </tr>
        @forelse($posts as $post)
        <tr>
            <td><a href="/boards/{{ $post->board }}">/{{ $post->board }}/</a></td>
            <td>{{ $post->id }}{{ $post->lead_id ? ' (resposta ao ' . $post->lead_id . ')' : '' }}</td>
            <td>{{ $post->assunto }}</td>
            <td>{{ strip_tags(substr($post->conteudo, 0, 120)) }}</td>
            <td>
                @php $arqsOriginais = $post->arquivos->where('thumb', '=', false); @endphp
                @foreach($arqsOriginais as $arq)
                    @if($arq->mime === 'image/jpeg' || $arq->mime === 'image/png' || $arq->mime === 'image/gif')
                        <a href="/storage/{{ $arq->filename }}" target="_blank">
                            <img class="img-responsive img-thumbnail" src="{{ \Storage::url($arq->filename) . ".thumb.jpg" }}" width="100px" height="100px">
                        </a>
                    @elseif($arq->mime === 'video/mp4' || $arq->mime === 'video/webm')
                        <img class="img-responsive vid-thumbnail" src="{{ \Storage::url($arq->filename) . ".thumb.png" }}" data-type="{{ $arq->mime }}" width="100px" height="100px">
                    @elseif($arq->mime === 'audio/mpeg')
                        <audio controls>
                            <source src="/storage/{{ $arq->filename }}" type="audio/mpeg">
                        </audio>
                    @else
                        <span title="{{ $arq->original_filename }}">{{ substr($arq->original_filename,0,30) }}</span>
                    @endif
                    <br>
                    <small>{{ $arq->original_filename }}</small><br>
                @endforeach
                {{ count($arqsOriginais) }} arquivo(s)
            </td>
            <td>{{ $post->created_at }}</td>
            <td>
                <div class="pull-left">
                    <form action="{{ route('pendentes.aprovar', ['post_id' => $post->id]) }}" method="post">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success">Aprovar</button>
                    </form>
                </div>
                <div class="pull-right">
                    <form action="{{ route('posts.destroy') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="siglaBoard" value="{{ $post->board }}">
                        <input type="hidden" name="postId" value="{{ $post->id }}">
                        <input type="hidden" name="senhadel" value="{{ $post->senhadel }}">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Reprovar e deletar o post {{ $post->id }}? Esta ação não pode ser desfeita.');">Reprovar</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">Nenhum post pendente de aprovação.</td>
        </tr>
        @endforelse
        </table>
    </div>
</div>
<br>
<div class="text-center">
    <a href="/admin"><button type="button" class="btn btn-primary">Voltar para o admin</button></a>
</div>

<script>
// Recarrega a pagina automaticamente a cada minuto (equivale a pressionar F5)
setTimeout(function () {
    window.location.reload();
}, 60000);
</script>
@endsection

@section('styles')
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: center;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
@endsection
